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
    const DEPLOY_TYPE = 'hotfix';

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
            if (!isset($argv[3])) {
                $this->hotfixOpen();
            } else {
                $this->hotfixOpenWithReleaseTag($argv[3]);
            }
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

        case 'state-all':
            $this->enableRelease();
            $this->hotfixState(false, true);
        break;

        case 'is':
            $this->enableRelease();
            $this->hotfixState(true);
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

        case 'destroy':
            $this->enableRelease();
            $this->hotfixDestroy();
        break;

        case 'destroy-clean':
            $this->enableRelease();
            $this->hotfixDestroy(true);
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
            throw new exception(sprintf(__('Already %1$s opened.'), Release::DEPLOY_TYPE));
        } elseif ($this->isHotfixOpen()) {
            throw new exception(sprintf(__('Already %1$s opened.'), Hotfix::DEPLOY_TYPE));
        }

        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", trim($repository));

        foreach ($repository as $value) {
            if (strpos($value, 'remotes/'.$this->deploy_repository_name.'/hotfix/') !== false) {
               throw new exception(sprintf(__('Already %1$s opened.'), self::DEPLOY_TYPE)."\n".$value);
            }
        }

        $hotfix_rep = 'hotfix/'.date('Ymdhis');

        $this->GitCmdExecuter->checkout('upstream/master');
        $this->GitCmdExecuter->checkout($hotfix_rep, array('-b'));

        $this->GitCmdExecuter->push('upstream', $hotfix_rep);
        $this->GitCmdExecuter->push($this->deploy_repository_name, $hotfix_rep);
    }
    /* ----------------------------------------- */


    /**
     * +-- リリースタグを指定してリリース開く
     *
     * @access      public
     * @param       string $tag_name
     * @return      void
     */
    public function hotfixOpenWithReleaseTag($tag_name)
    {
        if ($this->isReleaseOpen()) {
            throw new exception(sprintf(__('Already %1$s opened.'), Release::DEPLOY_TYPE));
        } elseif ($this->isHotfixOpen()) {
            throw new exception(sprintf(__('Already %1$s opened.'), Hotfix::DEPLOY_TYPE));
        }

        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", trim($repository));

        foreach ($repository as $value) {
            if (strpos($value, 'remotes/'.$this->deploy_repository_name.'/hotfix/') !== false) {
               throw new exception(sprintf(__('Already %1$s opened.'), self::DEPLOY_TYPE)."\n".$value);
            }
        }

        $hotfix_rep = 'hotfix/'.date('Ymdhis');

        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout('', array('-b', $hotfix_rep, 'refs/tags/'.$tag_name));

        $this->GitCmdExecuter->push('upstream', $hotfix_rep);
        $this->GitCmdExecuter->push($this->deploy_repository_name, $hotfix_rep);
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
            throw new exception(sprintf(__('%1$s is not open.'), self::DEPLOY_TYPE));
        }

        $repo = $this->getHotfixRepository();
        $this->deployTrack($repo);

        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->pull($this->deploy_repository_name, $repo);
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
            throw new exception(sprintf(__('%1$s is not open.'), self::DEPLOY_TYPE));
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
     * @param       bool $ck_only OPTIONAL:false
     * @param       bool $with_merge_commit OPTIONAL:false
     * @return      void
     */
    public function hotfixState($ck_only = false, $with_merge_commit = false)
    {
        if ($this->isHotfixOpen()) {
            if (!$ck_only) {
                $repo = $this->getHotfixRepository();
                $option = $with_merge_commit ? array() : array('--no-merges');
                $this->ncecho($this->GitCmdExecuter->log('deploy/master', $repo, $option));
            }

            $this->ncecho(sprintf(__('%1$s is open.'), self::DEPLOY_TYPE)."\n");

            return;
        }

        $this->ncecho(sprintf(__('%1$s is close.'), self::DEPLOY_TYPE)."\n");
    }

    /**
     * +-- コードを各環境に反映する
     *
     * @access      public
     * @return void
     */
    public function hotfixSync()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception(sprintf(__('%1$s is not open.'), self::DEPLOY_TYPE));
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
            throw new exception(sprintf(__('%1$s is not open.'), self::DEPLOY_TYPE));
        }

        $repo = $this->getHotfixRepository();

        $this->deployPush($repo);
    }
    /* ----------------------------------------- */


    /**
     * +-- hotfixを取り下げる
     *
     * @access      public
     * @return void
     *
     * @param bool $remove_local OPTIONAL:false
     */
    public function hotfixDestroy($remove_local = false)
    {
        if (!$this->isHotfixOpen()) {
            throw new exception(sprintf(__('%1$s is not open.'), self::DEPLOY_TYPE));
        }

        $repo = $this->getHotfixRepository();
        $this->deployDestroy($repo, self::DEPLOY_TYPE, $remove_local);
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
            throw new exception(sprintf(__('%1$s is not open.'), self::DEPLOY_TYPE));
        }

        $repo = $this->getHotfixRepository();
        $this->deployEnd($repo, self::DEPLOY_TYPE);
    }
    /* ----------------------------------------- */
}
