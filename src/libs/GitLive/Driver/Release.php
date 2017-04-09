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

        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        $this->Driver('Fetch')->deploy();

        switch ($argv[2]) {
        case 'open':
            $this->enableRelease();
            if (!isset($argv[3])) {
                $this->releaseOpen();
            } else {
                $this->releaseOpenWithReleaseTag($argv[3]);
            }

        break;
        case 'close':
            $this->enableRelease();
            $this->releaseClose();
        break;

        case 'close-force':
            $this->enableRelease();
            $this->releaseClose(true);
        break;

        case 'sync':
            $this->enableRelease();
            $this->releaseSync();
        break;

        case 'state':
            $this->enableRelease();
            $this->releaseState();
        break;

        case 'state-all':
            $this->enableRelease();
            $this->releaseState(false, true);
        break;

        case 'is':
            $this->enableRelease();
            $this->releaseState(true);
        break;

        case 'pull':
            $this->enableRelease();
            $this->releasePull();
        break;

        case 'push':
            $this->enableRelease();
            $this->releasePush();
        break;

        case 'track':
            $this->enableRelease();
            $this->releaseTrack();
        break;

        case 'destroy':
            $this->enableRelease();
            $this->releaseDestroy();
        break;

        case 'destroy-clean':
            $this->enableRelease();
            $this->releaseDestroy(true);
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
            throw new exception(sprintf(__('Already %1$s opened.'), 'release'));
        } elseif ($this->isHotfixOpen()) {
            throw new exception(sprintf(__('Already %1$s opened.'), 'hotfix'));
        }

        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", trim($repository));

        foreach ($repository as $value) {
            if (strpos($value, 'remotes/'.$this->deploy_repository_name.'/release/') !== false) {
               throw new exception(sprintf(__('Already %1$s opened.'), 'release')."\n".$value);
            }
        }

        $release_rep = 'release/'.date('Ymdhis');

        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout($release_rep, array('-b'));

        $this->GitCmdExecuter->push('upstream', $release_rep);
        $this->GitCmdExecuter->push($this->deploy_repository_name, $release_rep);
    }
    /* ----------------------------------------- */


    /**
     * +-- リリースタグを指定してリリース開く
     *
     * @access      public
     * @param       string $tag_name
     * @return      void
     */
    public function releaseOpenWithReleaseTag($tag_name)
    {
        if ($this->isReleaseOpen()) {
            throw new exception(sprintf(__('Already %1$s opened.'), 'release'));
        } elseif ($this->isHotfixOpen()) {
            throw new exception(sprintf(__('Already %1$s opened.'), 'hotfix'));
        }

        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", trim($repository));

        foreach ($repository as $value) {
            if (strpos($value, 'remotes/'.$this->deploy_repository_name.'/release/') !== false) {
               throw new exception(sprintf(__('Already %1$s opened.'), 'release')."\n".$value);
            }
        }

        $release_rep = 'release/'.date('Ymdhis');

        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout('', array('-b', $release_rep, 'refs/tags/'.$tag_name));

        $this->GitCmdExecuter->push('upstream', $release_rep);
        $this->GitCmdExecuter->push($this->deploy_repository_name, $release_rep);
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
            throw new exception(sprintf(__('%1$s is not open.'), 'release'));
        }

        $repo = $this->getReleaseRepository();

        $this->deployTrack($repo);
        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->pull($this->deploy_repository_name, $repo);
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
            throw new exception(sprintf(__('%1$s is not open.'), 'release'));
        }

        $repo = $this->getReleaseRepository();
        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->pull($this->deploy_repository_name, $repo);
    }
    /* ----------------------------------------- */


    /**
     * +-- リリースの状態を確かめる
     *
     * @access      public
     * @param       bool $ck_only OPTIONAL:false
     * @param       bool $with_merge_commit OPTIONAL:false
     * @return      void
     */
    public function releaseState($ck_only = false, $with_merge_commit = false)
    {
        if ($this->isReleaseOpen()) {
            if (!$ck_only) {
                $repo = $this->getReleaseRepository();
                $option = $with_merge_commit ? array() : array('--no-merges');
                $this->ncecho($this->GitCmdExecuter->log('deploy/master', $repo, $option));
            }

            $this->ncecho(sprintf(__('%1$s is open.'), 'release')."\n");

            return;
        }

        $this->ncecho(sprintf(__('%1$s is close.'), 'release')."\n");
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
            throw new exception(sprintf(__('%1$s is not open.'), 'release'));
        }

        $repo = $this->getReleaseRepository();
        $this->deploySync($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- コードをupstreamに反映する
     *
     * @access      public
     * @return void
     */
    public function releasePush()
    {
        if (!$this->isReleaseOpen()) {
            throw new exception(sprintf(__('%1$s is not open.'), 'release'));
        }

        $repo = $this->getReleaseRepository();
        $this->deployPush($repo);
    }
    /* ----------------------------------------- */


    /**
     * +-- リリースを取り下げる
     *
     * @access      public
     * @return void
     *
     * @param bool $remove_local OPTIONAL:false
     */
    public function releaseDestroy($remove_local = false)
    {
        if (!$this->isReleaseOpen()) {
            throw new exception(sprintf(__('%1$s is not open.'), 'release'));
        }

        $repo = $this->getReleaseRepository();
        $this->deployDestroy($repo, 'release', $remove_local);
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
        if (!$this->isReleaseOpen()) {
            throw new exception(sprintf(__('%1$s is not open.'), 'release'));
        }

        $repo = $this->getReleaseRepository();
        $this->deployEnd($repo, 'release', $force);
    }
    /* ----------------------------------------- */
}
