<?php
/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */

namespace GitLive\Driver;

use App;
use GitLive\Support\InteractiveShellInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
class InitDriver extends DriverBase
{

    /**
     *  初期化処理します
     *
     * @access      public
     * @param InputInterface $input
     * @return void
     * @throws Exception
     */
    public function init(InputInterface $input)
    {
        $auto_clone_dir = null;
        $is_auto_clone_dir = false;
        if (!$input->getArgument('upstream_repository')) {
            $clone_repository = $this->interactiveShell(__('Please enter only your remote-repository.'));
            $upstream_repository = $this->interactiveShell(__('Please enter common remote-repository.'));
            $deploy_repository = $this->interactiveShell([
                __('Please enter deploying dedicated remote-repository.'),
                __('If you return in the blank, it becomes the default setting.'),
                "default:{$upstream_repository}",
            ], $upstream_repository);

            $match = null;
            $is_auto_clone_dir = mb_ereg('/([^/]+?)(\.git)?$', $clone_repository, $match);
            if ($is_auto_clone_dir) {
                $auto_clone_dir = $match[1];
            }

            $clone_dir = $this->interactiveShell([
                __('Please enter work directory path.'),
                __('If you return in the blank, it becomes the default setting.'),
                "default:{$auto_clone_dir}",
            ], $auto_clone_dir);
        } else {
            $clone_repository = $input->getArgument('clone_repository');
            $upstream_repository = $input->getArgument('upstream_repository');
            $deploy_repository = $input->getArgument('deploy_repository');
            $clone_dir = $input->getArgument('clone_dir');

            if ($deploy_repository === null) {
                $deploy_repository = $upstream_repository;
            }

            if ($clone_dir === null) {
                $match = null;
                $is_auto_clone_dir = mb_ereg('/([^/]+?)(\.git)?$', $clone_repository, $match);
                if ($is_auto_clone_dir) {
                    $auto_clone_dir = $match[1];
                    $clone_dir = $auto_clone_dir;
                }
            }
        }

        if (empty($clone_dir)) {
            if (!$is_auto_clone_dir) {
                throw new Exception(__('Could not automatically get the local directory.'));
            }

            $clone_dir = $auto_clone_dir;
        }

        $this->GitCmdExecuter->copy(['--recursive', $clone_repository, $clone_dir]);

        $this->chdir($clone_dir);

        $this->GitCmdExecuter->remote(['add', 'upstream', $upstream_repository]);

        if ($deploy_repository !== null) {
            $this->GitCmdExecuter->remote(['add', 'deploy', $deploy_repository]);
        }
    }

    /**
     * @param string $text
     * @param bool   $using_default
     * @return string
     */
    protected function interactiveShell($text, $using_default = false)
    {
        try {
            return App::make(InteractiveShellInterface::class)
                ->interactiveShell($text, $using_default);
        } catch (\Exception $exception) {

        }
    }

    /**
     *  諸々初期化します
     *
     * @access      public
     * @return void
     * @throws Exception
     */
    public function start()
    {
        $this->GitCmdExecuter->stash('-u');
        $this->Driver(FetchDriver::class)->clean();
        $this->Driver(FetchDriver::class)->all();

        $Config = $this->Driver(ConfigDriver::class);

        $this->GitCmdExecuter->checkout($Config->develop());
        $this->GitCmdExecuter->pull('upstream', $Config->develop());
        $this->GitCmdExecuter->push('origin', $Config->develop());

        $this->GitCmdExecuter->checkout($Config->master());
        $this->GitCmdExecuter->pull('upstream', $Config->master());
        $this->GitCmdExecuter->push('origin', $Config->master());
    }


    /**
     *  諸々リセットして初期化します
     *
     * @access      public
     * @return void
     * @throws Exception
     */
    public function restart()
    {
        $is_yes = $this->interactiveShell(__('Rebuild? yes/no'));
        if ($is_yes !== 'yes') {
            return;
        }

        $this->Driver(FetchDriver::class)->all();
        $this->GitCmdExecuter->checkout('temp', ['-b']);
        $this->GitCmdExecuter->branch(['-d', 'develop']);
        $this->GitCmdExecuter->branch(['-d', 'master']);
        $this->GitCmdExecuter->push('origin', ':develop');
        $this->GitCmdExecuter->push('origin', ':master');

        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout('develop', ['-b']);
        $this->GitCmdExecuter->push('origin', 'develop');

        $this->GitCmdExecuter->checkout('upstream/master');
        $this->GitCmdExecuter->checkout('master', ['-b']);
        $this->GitCmdExecuter->push('origin', 'master');
        $this->GitCmdExecuter->fetch(['--all']);
        $this->GitCmdExecuter->fetch(['-p']);
    }

}
