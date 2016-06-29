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
class Init extends DriverBase
{


    /**
     * +-- 初期化処理します
     *
     * @access      public
     * @param  var_text $clone_repository
     * @param  var_text $upstream_repository
     * @param  var_text $deploy_repository
     * @param  var_text $clone_dir
     * @return void
     */
    public function init()
    {
        $argv = $this->getArgv();
        if (!isset($argv[3])) {
            while (true) {
                $this->ncecho(_('Please enter only your remote-repository.')."\n");
                $this->ncecho(':');
                $clone_repository = trim(fgets(STDIN, 1000));
                if ($clone_repository === '') {
                    $this->ncecho(':');
                    continue;
                }

                break;
            }

            while (true) {
                $this->ncecho(_('Please enter common remote-repository.')."\n");
                $this->ncecho(':');
                $upstream_repository = trim(fgets(STDIN, 1000));

                if ($upstream_repository === '') {
                    $this->ncecho(':');
                    continue;
                }

                break;
            }

            while (true) {
                $this->ncecho(_('Please enter deploying dedicated remote-repository.')."\n");
                $this->ncecho(_('If you return in the blank, it becomes the default setting.')."\n");
                $this->ncecho("default:{$upstream_repository}"."\n");
                $this->ncecho(':');

                $deploy_repository = trim(fgets(STDIN, 1000));

                if ($deploy_repository === '') {
                    $deploy_repository = $upstream_repository;
                }

                break;
            }

            $is_auto_clone_dir = mb_ereg('/([^/]+?)(\.git)?$', $clone_repository, $match);
            while (true) {
                $this->ncecho(_('Please enter work directory path.')."\n");
                $this->ncecho(_('If you return in the blank, it becomes the default setting.')."\n");
                $this->ncecho("default:{$match[1]}"."\n");
                $this->ncecho(':');
                $clone_dir = trim(fgets(STDIN, 1000));

                if ($clone_dir === '') {
                    $clone_dir = null;
                }

                break;
            }
        } else {
            $clone_repository = $argv[2];

            $upstream_repository = $argv[3];
            if (isset($argv[5])) {
                $deploy_repository = $argv[4];
                $clone_dir         = $argv[5];
            } elseif (!isset($argv[4])) {
                $deploy_repository = null;
                $clone_dir         = null;
            } elseif (strpos($argv[4], 'git') === 0 || strpos($argv[4], 'https:') === 0 || is_dir(realpath($argv[4]).'/.git/')) {
                $deploy_repository = $argv[4];
                $clone_dir         = null;
            } else {
                $clone_dir         = $argv[4];
                $deploy_repository = null;
            }
        }

        if ($clone_dir === null) {
            if (!$is_auto_clone_dir) {
                $this->ncecho(_('ローカルディレクトリを自動取得できませんでした。'));

                return;
            }

            $clone_dir = getcwd().DIRECTORY_SEPARATOR.$match[1];
        }

        $this->GitCmdExecuter->copy(array('--recursive', $clone_repository, $clone_dir));

        chdir($clone_dir);
        $this->GitCmdExecuter->remote(array('add', 'upstream', $upstream_repository));

        if ($deploy_repository !== null) {
            $this->GitCmdExecuter->remote(array('add', 'deploy', $deploy_repository));
        }
    }

    /* ----------------------------------------- */


    /**
     * +-- 諸々初期化します
     *
     * @access      public
     * @return void
     */
    public function start()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $this->GitCmdExecuter->pull('upstream', 'develop');
        $this->GitCmdExecuter->push('origin', 'develop');
        $this->GitCmdExecuter->pull('upstream', 'master');
        $this->GitCmdExecuter->push('origin', 'master');
    }

    /* ----------------------------------------- */

    /**
     * +-- 諸々リセットして初期化します
     *
     * @access      public
     * @return void
     */
    public function restart()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $this->GitCmdExecuter->checkout('temp', array('-b'));
        $this->GitCmdExecuter->branch(array('-d', 'develop'));
        $this->GitCmdExecuter->branch(array('-d', 'master'));
        $this->GitCmdExecuter->push('origin', ':develop');
        $this->GitCmdExecuter->push('origin', ':master');

        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout('develop', array('-b'));
        $this->GitCmdExecuter->push('origin', 'develop');

        $this->GitCmdExecuter->checkout('upstream/master');
        $this->GitCmdExecuter->checkout('master', array('-b'));
        $this->GitCmdExecuter->push('origin', 'master');
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
    }

    /* ----------------------------------------- */
}
