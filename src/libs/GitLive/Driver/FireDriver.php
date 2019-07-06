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
    public function cdRoot()
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

        $res = trim($this->GitCmdExecutor->config(['--get', 'user.name']));

        if ($res === '') {
            $res = (string)trim($this->GitCmdExecutor->config(['--get', 'user.email']));
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
     * @return string
     * @throws Exception
     */
    public function makeFireBranchName(): string
    {
        return 'fire/' . $this->getSnakeUserName() . '/' . date('YmdHis') . '/' . $this->getSelfBranch();
    }

    /**
     *
     */
    public function chNewBranch()
    {
        $this->GitCmdExecutor->checkout($this->makeFireBranchName(), ['-b']);
    }

    /**
     * @param $message
     */
    public function commit($message)
    {
        $this->GitCmdExecutor->add(['-A']);
        $this->GitCmdExecutor->commit($message);
    }

    public function fire($message)
    {
        $message = trim($message);
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
