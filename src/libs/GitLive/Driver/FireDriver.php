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

/**
 * Class RefDriver
 *
 * Operations like git reset command
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
class FireDriver extends DriverBase
{
    /**
     * リポジトリのルートに移動する
     */
    public function cdRoot(): void
    {
        $root = $this->GitCmdExecutor->topLevelDir() . DIRECTORY_SEPARATOR;

        $this->chdir($root);
    }

    /**
     * @return string
     */
    public function getSnakeUserName(): string
    {
        $delimiters = [' ', "\t", "\r", "\n", "\f", "\v", '@', '/', '+', '~', '{', '}', '[', ']', '?', ':', '*', '\\'];

        $res = trim((string)$this->GitCmdExecutor->config(['--get', 'user.name']));

        if ($res === '') {
            $res = trim((string)$this->GitCmdExecutor->config(['--get', 'user.email']));
        }

        if ($res === '' && function_exists('get_current_user')) {
            $res = trim(get_current_user());
        }

        if ($res === '') {
            $res = 'git_live';
        }

        return str_replace($delimiters, '_', $res);
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ErrorException
     * @return string
     */
    public function makeFireBranchName(): string
    {
        $Config = $this->Driver(ConfigDriver::class);

        $fire_prefix = $Config->firePrefix();

        return $fire_prefix . $this->getSnakeUserName() . '/' . date('YmdHis') . '/' . $this->getSelfBranch();
    }

    /**
     *
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     */
    public function chNewBranch(): void
    {
        $this->GitCmdExecutor->checkout($this->makeFireBranchName(), ['-b']);
    }

    /**
     * @param $message
     */
    public function commit($message): void
    {
        $this->GitCmdExecutor->add(['-A']);
        $this->GitCmdExecutor->commit($message);
    }

    /**
     * @param $message
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     */
    public function fire($message): void
    {
        $message = trim((string)$message);
        $self_branch = $this->getSelfBranch();
        if ($message === '') {
            $message = 'Git Live Fire!! Branch...' . $self_branch;
        }

        $this->cdRoot();
        $this->chNewBranch();
        $this->commit($message);
        $this->GitCmdExecutor->push('origin', $this->getSelfBranch());

        $this->stashPush($this->getSelfBranch());
    }
}
