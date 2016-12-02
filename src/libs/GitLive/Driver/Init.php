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
            $clone_repository    = $this->interactiveShell(_('Please enter only your remote-repository.'));
            $upstream_repository = $this->interactiveShell(_('Please enter common remote-repository.'));
            $deploy_repository   = $this->interactiveShell(array(
                _('Please enter deploying dedicated remote-repository.'),
                _('If you return in the blank, it becomes the default setting.'),
                "default:{$upstream_repository}",
                ), $upstream_repository);
            $is_auto_clone_dir = mb_ereg('/([^/]+?)(\.git)?$', $clone_repository, $match);
            $auto_clone_dir    = null;
            if ($is_auto_clone_dir) {
                $auto_clone_dir = $match[1];
            }

            $clone_dir = $this->interactiveShell(array(
                _('Please enter work directory path.'),
                _('If you return in the blank, it becomes the default setting.'),
                "default:{$auto_clone_dir}",
                ), $auto_clone_dir);
        } else {
            $clone_repository    = $argv[2];
            $is_auto_clone_dir   = false;
            $upstream_repository = $argv[3];
            if (isset($argv[5])) {
                $deploy_repository = $argv[4];
                $clone_dir         = $argv[5];
            } elseif (!isset($argv[4])) {
                $deploy_repository = $argv[3];
                $clone_dir         = null;
                $is_auto_clone_dir = mb_ereg('/([^/]+?)(\.git)?$', $clone_repository, $match);
                $auto_clone_dir    = null;
                if ($is_auto_clone_dir) {
                    $auto_clone_dir    = $match[1];
                    $clone_dir         = $auto_clone_dir;
                }
            } elseif (strpos($argv[4], 'git') === 0 || strpos($argv[4], 'https:') === 0 || is_dir(realpath($argv[4]).'/.git/')) {
                $deploy_repository = $argv[4];
                $clone_dir         = null;
                $is_auto_clone_dir = mb_ereg('/([^/]+?)(\.git)?$', $clone_repository, $match);
                $auto_clone_dir    = null;
                if ($is_auto_clone_dir) {
                    $auto_clone_dir    = $match[1];
                    $clone_dir         = $auto_clone_dir;
                }
            } else {
                $clone_dir         = $argv[4];
                $deploy_repository = null;
            }
        }

        if (empty($clone_dir)) {
            if (!$is_auto_clone_dir) {
                throw new exception(_('Could not automatically get the local directory.'));
            }
            $clone_dir = $auto_clone_dir;
        }

        $this->GitCmdExecuter->copy(array('--recursive', $clone_repository, $clone_dir));

        $this->chdir($clone_dir);
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
        $this->Driver('Fetch')->all();
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
        $this->Driver('Fetch')->all();
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
