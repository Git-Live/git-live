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
    public function init(InputInterface $input): void
    {
        $auto_clone_dir = null;
        $is_auto_clone_dir = false;
        if (!$input->getArgument('upstream_repository')) {
            $clone_repository = $this->interactiveShell(__('Please enter only your remote-repository.'));
            $upstream_repository = $this->interactiveShell(__('Please enter common remote-repository.'));
            $deploy_repository = $this->interactiveShell([
                __('Please enter deploying dedicated remote-repository.'),
                __('If you return in the blank, it becomes the default setting.'),
                "default:" . $upstream_repository,
            ], $upstream_repository);

            $match = null;
            $is_auto_clone_dir = mb_ereg('/([^/]+?)(\.git)?$', $clone_repository, $match);
            if ($is_auto_clone_dir) {
                $auto_clone_dir = $match[1];
            }

            $clone_dir = $this->interactiveShell([
                __('Please enter work directory path.'),
                __('If you return in the blank, it becomes the default setting.'),
                "default:" . $auto_clone_dir,
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

        $this->GitCmdExecutor->clone(['--recursive', $clone_repository, $clone_dir]);

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
     * @param bool $without_remote_change
     * @throws \GitLive\Driver\Exception
     * @throws \ErrorException
     * @return void
     */
    public function start(bool $without_remote_change = true): void
    {
        $this->GitCmdExecutor->stash(['-u']);
        if (!$this->GitCmdExecutor->isGitInit() || !$this->isLocalInitialized()) {
            throw new Exception(__('fatal: Not initialized.') . __('use: `git live branch-init`'));
        }

        $this->Driver(FetchDriver::class)->clean();
        $this->Driver(FetchDriver::class)->all();

        $Config = $this->Driver(ConfigDriver::class);

        $this->GitCmdExecutor->checkout($Config->develop());

        $this->GitCmdExecutor->pull('upstream', $Config->develop());

        if (!$without_remote_change) {
            $this->GitCmdExecutor->push('origin', $Config->develop());
        }

        $this->GitCmdExecutor->checkout($Config->master());
        $this->GitCmdExecutor->pull('upstream', $Config->master());

        if (!$without_remote_change) {
            $this->GitCmdExecutor->push('origin', $Config->master());
        }

        // tag
        $this->GitCmdExecutor->tagPull('upstream');

        if (!$without_remote_change) {
            $this->GitCmdExecutor->tagPush('origin');
        }
    }

    /**
     *  諸々リセットして初期化します
     *
     * @access      public
     * @throws \GitLive\Driver\Exception
     * @throws \ErrorException
     * @return void
     */
    public function restart(): void
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
     * @param bool $is_default
     * @param bool $is_force
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return void
     */
    public function branchInit(bool $is_default = false, bool $is_force = false)
    {
        $Config = $this->Driver(ConfigDriver::class);
        $Branch = $this->Driver(BranchDriver::class);
        $Remote = $this->Driver(RemoteDriver::class);
        if (!$this->GitCmdExecutor->isGitInit()) {
            $this->GitCmdExecutor->init();
        }

        if (!$this->GitCmdExecutor->isHeadless() && !$this->GitCmdExecutor->isCleanWorkingTree()) {
            throw new Exception(__('fatal: Working tree is not clean.'));
        }

        // configのセット
        if (!$is_default) {
            $Config->interactiveConfigurations();
        }

        // リモートブランチのセット
        if (!$is_force) {
            $is_yes = $this->interactiveShell(__('Do you want to configure Remote settings? yes/no'));

            if ($is_yes === 'yes') {
                $Remote->interactiveRemoteAdd();
            }
        }

        if ($this->isLocalInitialized()) {
            if ($is_force) {
                $this->start();

                return;
            }

            throw new Exception(__('Already initialized for git-live.') .
                __('To force reinitialization, use: `git live branch:init -f`.'));
        }

        $this->Driver(FetchDriver::class)->all();

        // Creation of master
        if (!$Branch->hasMasterBranch()) {
            if ($Branch->isRemoteBranchExistsSimple('remotes/origin/' . $Config->master())) {
                $this->GitCmdExecutor->branch([$Config->master(), 'remotes/origin/' . $Config->master()]);
            } else {
                throw new Exception(__('fatal: Local branch "' . $Config->master() . '" does not exist.'));
            }
        }

        // Creation of develop
        if (!$Branch->hasDevelopBranch()) {
            if ($Branch->isRemoteBranchExistsSimple('remotes/origin/' . $Config->develop())) {
                $this->GitCmdExecutor->branch([$Config->develop(), 'remotes/origin/' . $Config->develop()]);
            } elseif ($Branch->isRemoteBranchExistsSimple('remotes/origin/' . $Config->master())) {
                $this->GitCmdExecutor->branch([$Config->develop(), 'remotes/origin/' . $Config->master()]);
            } else {
                $this->GitCmdExecutor->branch(['--no-track', $Config->develop(), $Config->master()]);
            }
        }
    }

    /**
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return bool
     */
    public function isLocalInitialized()
    {
        $Branch = $this->Driver(BranchDriver::class);

        return $Branch->hasMasterBranch() && $Branch->hasDevelopBranch();
    }
}
