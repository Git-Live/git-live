<?php
/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
namespace GitLive\Driver;

/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
class Hotfix extends DeployBase
{

    /**
     * +-- hotfixを実行する
     *
     * @access      public
     * @return void
     */
    public function hotfix()
    {
        $argv = $this->getArgv();
        if (!isset($argv[2])) {
            $this->Driver('Help')->help();

            return;
        }

        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        $this->Driver('Fetch')->deploy();

        switch ($argv[2]) {
        case 'open':
            $this->enableRelease();
            $this->hotfixOpen();
        break;
        case 'close':
            $this->enableRelease();
            $this->hotfixClose();
        break;
        case 'sync':
            $this->enableRelease();
            $this->hotfixSync();
        break;
        case 'state':
            $this->enableRelease();
            $this->hotfixState();
        break;
        case 'pull':
            $this->enableRelease();
            $this->hotfixPull();
        break;
        case 'push':
            $this->enableRelease();
            $this->hotfixPush();
        break;

        case 'track':
            $this->enableRelease();
            $this->hotfixTrack();
        break;

        default:
            $this->Driver('Help')->help();
        break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- hotfixを開く
     *
     * @access      public
     * @return void
     */
    public function hotfixOpen()
    {
        if ($this->isReleaseOpen()) {
            throw new exception(_('既にrelease open されています。'));
        } elseif ($this->isHotfixOpen()) {
            throw new exception(_('既にhotfix open されています。'));
        }

        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", trim($repository));
        foreach ($repository as $value) {
            if (strpos($value, 'remotes/'.$this->deploy_repository_name.'/hotfix/') !== false) {
                throw new exception(_('既にhotfix open されています。'.$value));
            }
        }

        $hotfix_rep = 'hotfix/'.date('Ymdhis');

        $this->GitCmdExecuter->checkout('upstream/master');
        $this->GitCmdExecuter->checkout($hotfix_rep, array('-b'));

        $this->GitCmdExecuter->push('upstream', $hotfix_rep);
        $this->GitCmdExecuter->push('deploy', $hotfix_rep);
    }
    /* ----------------------------------------- */

    /**
     * +-- 誰かが開けたhotfixをトラックする
     *
     * @access      public
     * @return void
     */
    public function hotfixTrack()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception(_('hotfix openされていません。'));
        }

        $repo = $this->getHotfixRepository();
        $this->deployTrack($repo);

        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->pull('deploy', $repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- 誰かが開けたhotfixをpullする
     *
     * @access      public
     * @return void
     */
    public function hotfixPull()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception(_('hotfix openされていません。'));
        }

        $repo = $this->getHotfixRepository();
        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->pull($this->deploy_repository_name, $repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- hotfixの状態を確かめる
     *
     * @access      public
     * @return void
     */
    public function hotfixState()
    {
        if ($this->isHotfixOpen()) {
            $repo = $this->getHotfixRepository();
            $this->ncecho($this->GitCmdExecuter->log('deploy/master', $repo));
            $this->ncecho("hotfix is open.\n");

            return;
        }

        $this->ncecho("hotfix is close.\n");
    }
    /* ----------------------------------------- */

    /**
     * +-- コードを各環境に反映する
     *
     * @access      public
     * @return void
     */
    public function hotfixSync()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception(_('hotfix openされていません。'));
        }

        $repo = $this->getHotfixRepository();

        $this->deploySync($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- コードを各環境に反映する
     *
     * @access      public
     * @return void
     */
    public function hotfixPush()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception(_('hotfix openされていません。'));
        }

        $repo = $this->getHotfixRepository();

        $this->deployPush($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- hotfixを閉じる
     *
     * @access      public
     * @return void
     */
    public function hotfixClose()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception(_('hotfix openされていません。'));
        }

        $repo = $this->getHotfixRepository();
        $this->deployEnd($repo, 'hotfix');
    }
    /* ----------------------------------------- */
}
