<?php

/**
 * This file is part of Git-Live
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id\$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 */

namespace GitLive\Driver;

use App;
use GitLive\Support\FileSystem;
use GitLive\Support\InteractiveShellInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class InitDriver
 *
 * @category   GitCommand
 * @package    GitLive\Driver
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-08
 */
class InitDriver extends DriverBase
{
    /**
     *  初期化処理します
     *
     * @access      public
     * @param InputInterface $input
     * @throws Exception
     * @return void
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

        $this->GitCmdExecutor->copy(['--recursive', $clone_repository, $clone_dir]);

        $this->chdir($clone_dir);

        $this->GitCmdExecutor->remote(['add', 'upstream', $upstream_repository]);

        if ($deploy_repository !== null) {
            $this->GitCmdExecutor->remote(['add', 'deploy', $deploy_repository]);
        }
    }

    /**
     *  諸々初期化します
     *
     * @access      public
     * @throws Exception
     * @return void
     */
    public function start()
    {
        $this->GitCmdExecutor->stash(['-u']);
        $this->Driver(FetchDriver::class)->clean();
        $this->Driver(FetchDriver::class)->all();

        $Config = $this->Driver(ConfigDriver::class);

        $this->GitCmdExecutor->checkout($Config->develop());
        $this->GitCmdExecutor->pull('upstream', $Config->develop());
        $this->GitCmdExecutor->push('origin', $Config->develop());

        $this->GitCmdExecutor->checkout($Config->master());
        $this->GitCmdExecutor->pull('upstream', $Config->master());
        $this->GitCmdExecutor->push('origin', $Config->master());
    }

    /**
     *  諸々リセットして初期化します
     *
     * @access      public
     * @throws Exception
     * @throws \ReflectionException
     * @return void
     */
    public function restart()
    {
        $is_yes = $this->interactiveShell(__('Rebuild? yes/no'));

        if ($is_yes !== 'yes') {
            return;
        }

        if ($this->isRisky()) {
            throw new Exception(__('It is very risky project.') . "\n" . $this->GitCmdExecutor->remote(['-v'], true));
        }

        $this->Driver(FetchDriver::class)->clean();
        $Config = $this->Driver(ConfigDriver::class);
        $this->Driver(FetchDriver::class)->all();

        $this->GitCmdExecutor->checkout('temp', ['-b']);
        $this->GitCmdExecutor->branch(['-d', $Config->develop()]);
        $this->GitCmdExecutor->branch(['-d', $Config->master()]);
        $this->GitCmdExecutor->push('origin', ':' . $Config->develop());
        $this->GitCmdExecutor->push('origin', ':' . $Config->master());

        $this->GitCmdExecutor->checkout('remotes/upstream/' . $Config->develop());
        $this->GitCmdExecutor->checkout($Config->develop(), ['-b']);
        $this->GitCmdExecutor->push('origin', $Config->develop());

        $this->GitCmdExecutor->checkout('remotes/upstream/' . $Config->master());
        $this->GitCmdExecutor->checkout($Config->master(), ['-b']);
        $this->GitCmdExecutor->push('origin', $Config->master());
        $this->Driver(FetchDriver::class)->all();
    }

    /**
     * @param array|string $text
     * @param bool   $using_default
     * @return string
     */
    protected function interactiveShell($text, $using_default = false)
    {
        try {
            return App::make(InteractiveShellInterface::class)
                ->interactiveShell($text, $using_default);
        } catch (\Exception $exception) {
            return '';
        }
    }
}
