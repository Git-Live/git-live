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
class Release extends DeployBase
{

    /**
     * +-- releaseを実行する
     *
     * @access      public
     * @return void
     */
    public function release()
    {
        $argv = $this->getArgv();
        if (!isset($argv[2])) {
            $this->Driver('Help')->help();

            return;
        }

        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p', 'deploy'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
        $this->enableRelease();
        switch ($argv[2]) {
        case 'open':
            $this->releaseOpen();
        break;

        case 'close':
            $this->releaseClose();
        break;

        case 'close-force':
            $this->releaseClose(true);
        break;

        case 'sync':
            $this->releaseSync();
        break;

        case 'state':
            $this->releaseState();
        break;

        case 'pull':
            $this->releasePull();
        break;

        case 'push':
            $this->releasePush();
        break;

        default:
            $this->Driver('Help')->help();
        break;
        }
    }

    /* ----------------------------------------- */

    /**
     * +-- リリースを開く
     *
     * @access      public
     * @return void
     */
    public function releaseOpen()
    {
        if ($this->isReleaseOpen()) {
            throw new exception(_('既にrelease open されています。'));
        } elseif ($this->isHotfixOpen()) {
            throw new exception(_('既にhotfix open されています。'));
        }

        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", trim($repository));

        foreach ($repository as $value) {
            if (strpos($value, 'remotes/'.$this->deploy_repository_name.'/release/') !== false) {
                throw new exception(_('既にrelease open されています。'.$value));
            }
        }

        $release_rep = 'release/'.date('Ymdhis');

        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout($release_rep, array('-b'));

        $this->GitCmdExecuter->push('upstream', $release_rep);
        $this->GitCmdExecuter->push('deploy', $release_rep);
    }

    /* ----------------------------------------- */

    /**
     * +-- 誰かが開けたリリースをトラックする
     *
     * @access      public
     * @return void
     */
    public function releaseTrack()
    {
        if (!$this->isReleaseOpen()) {
            throw new exception(_('release openされていません。'));
        }

        $repo = $this->getReleaseRepository();
        $this->GitCmdExecuter->pull('deploy', $repo);
        $this->GitCmdExecuter->checkout($repo);
    }

    /* ----------------------------------------- */

    /**
     * +-- 誰かが開けたリリースをpullする
     *
     * @access      public
     * @return void
     */
    public function releasePull()
    {
        if (!$this->isReleaseOpen()) {
            throw new exception(_('release openされていません。'));
        }

        $repo = $this->getReleaseRepository();
        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->checkout($repo);
    }

    /* ----------------------------------------- */

    /**
     * +-- リリースの状態を確かめる
     *
     * @access      public
     * @return void
     */
    public function releaseState()
    {
        if ($this->isReleaseOpen()) {
            $repo = $this->getReleaseRepository();
            $this->ncecho($this->GitCmdExecuter->log('master', $repo));
            $this->ncecho("release is open.\n");

            return;
        }

        $this->ncecho("release is close.\n");
    }

    /* ----------------------------------------- */

    /**
     * +-- コードを各環境に反映する
     *
     * @access      public
     * @return void
     */
    public function releaseSync()
    {
        if (!$this->isReleaseOpen()) {
            throw new exception(_('release openされていません。'));
        }

        $repo = $this->getReleaseRepository();
        $this->deploySync($repo);
    }

    /* ----------------------------------------- */

    /**
     * +-- コードを各環境に反映する
     *
     * @access      public
     * @return void
     */
    public function releasePush()
    {
        if (!$this->isReleaseOpen()) {
            throw new exception(_('release openされていません。'));
        }

        $repo = $this->getReleaseRepository();
        $this->deployPush($repo);
    }

    /* ----------------------------------------- */

    /**
     * +-- リリースを閉じる
     *
     * @access      public
     * @return void
     *
     * @param bool $force OPTIONAL:false
     */
    public function releaseClose($force = false)
    {
        $argv = $this->getArgv();
        if (!$this->isReleaseOpen()) {
            throw new exception(_('release openされていません。'));
        }

        $repo = $this->getReleaseRepository();
        $this->deployEnd($repo, 'release', $force);
    }

    /* ----------------------------------------- */
}
