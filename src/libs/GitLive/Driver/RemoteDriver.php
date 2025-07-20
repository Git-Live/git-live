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

class RemoteDriver extends DriverBase
{
    /**
     * @param string $remote_name
     * @return bool
     */
    public function remoteExists(string $remote_name): bool
    {
        return !$this->command->isError('git remote get-url ' . $remote_name);
    }

    /**
     * @param string $remote_name
     * @return string
     */
    public function getUrl(string $remote_name): string
    {
        return (string)$this->GitCmdExecutor->remote(['get-url', $remote_name]);
    }

    /**
     * @param string $remote_name
     * @param string $url
     * @return void
     */
    public function add(string $remote_name, string $url): void
    {
        $this->GitCmdExecutor->remote(['add', $remote_name, $url]);
    }

    /**
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return void
     */
    public function interactiveRemoteAdd(): void
    {
        $Config = $this->Driver(ConfigDriver::class);
        if ($this->remoteExists('origin')) {
            $origin_repository = $this->interactiveShell(__('Please enter only your remote-repository.'));
            $this->add('origin', $origin_repository);
        }

        if ($this->remoteExists('upstream')) {
            $upstream_repository = $this->interactiveShell(__('Please enter common remote-repository.'));
            $this->add('upstream', $upstream_repository);
        }

        if ($this->remoteExists($Config->deployRemote())) {
            $upstream_repository = $this->getUrl('upstream');
            $deploy_repository = $this->interactiveShell([
                __('Please enter deploying dedicated remote-repository.'),
                __('If you return in the blank, it becomes the default setting.'),
                "default:" . $upstream_repository,
            ], $upstream_repository);
            $this->add($Config->deployRemote(), $deploy_repository);
        }
    }
}
