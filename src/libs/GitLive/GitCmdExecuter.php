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

namespace GitLive;

use GitLive\Support\SystemCommandInterface;

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
class GitCmdExecuter
{
    protected $command;

    /**
     * GitCmdExecuter constructor.
     * @param SystemCommandInterface $command
     */
    public function __construct(SystemCommandInterface $command)
    {
        $this->command = $command;
    }

    /**
     *
     *
     * @return string
     */
    public function fetchPullRequest()
    {
        $cmd = "git fetch upstream '+refs/pull/*:refs/remotes/pr/*'";

        return $this->exec($cmd);
    }

    public function config(array $options = [])
    {
        $cmd = $this->createCmd('config', $options);

        return $this->exec($cmd, true);
    }

    public function tag(array $options = [])
    {
        $cmd = $this->createCmd('tag', $options);

        return $this->exec($cmd);
    }

    public function copy(array $options = [])
    {
        $cmd = $this->createCmd('clone', $options);

        return $this->exec($cmd);
    }

    public function remote(array $options = [])
    {
        $cmd = $this->createCmd('remote', $options);

        return $this->exec($cmd);
    }

    public function status(array $options = [])
    {
        $cmd = $this->createCmd('status', $options);

        return $this->exec($cmd);
    }

    public function diff(array $options = [])
    {
        $cmd = $this->createCmd('diff', $options);

        return $this->exec($cmd);
    }

    public function merge($branch, array $options = [])
    {
        $cmd = $this->createCmd('merge', $options);
        $cmd .= ' ' . $branch;

        return $this->exec($cmd);
    }

    public function fetch(array $options = [])
    {
        $cmd = $this->createCmd('fetch', $options);

        return $this->exec($cmd);
    }

    public function clean(array $options = [])
    {
        $cmd = 'git clean -df';
        if ($options) {
            $cmd = $this->createCmd('clean', $options);
        }

        return $this->exec($cmd);
    }

    public function reset(array $options = [])
    {
        $cmd = 'git reset --hard HEAD';
        if ($options) {
            $cmd = $this->createCmd('reset', $options);
        }

        return $this->exec($cmd);
    }

    public function checkout($branch, array $options = [])
    {
        $cmd = $this->createCmd('checkout', $options);
        $cmd .= ' ' . $branch;

        return $this->exec($cmd);
    }

    public function branch(array $options = [], $quiet = false)
    {
        $cmd = $this->createCmd('branch', $options);

        return $this->exec($cmd, $quiet);
    }

    public function pull($remote, $branch = '')
    {
        $cmd = $this->createCmd('pull', [$remote, $branch]);

        return $this->exec($cmd);
    }

    public function push($remote, $branch = '')
    {
        $cmd = $this->createCmd('push', [$remote, $branch]);

        return $this->exec($cmd);
    }

    public function tagPush($remote)
    {
        $cmd = $this->createCmd('push', [$remote, '--tags']);

        return $this->exec($cmd);
    }

    public function log($left, $right, $option = '')
    {
        if (empty($option)) {
            $cmd = $this->createCmd('log', ['--pretty=fuller', '--name-status', $left . '..' . $right]);
        } else {
            $cmd = $this->createCmd('log', ['--pretty=fuller', '--name-status', $option, $left . '..' . $right]);
        }

        return $this->exec($cmd, true);
    }

    public function stash(array $options = [])
    {
        $cmd = $this->createCmd('stash', $options);

        return $this->exec($cmd);
    }



    /**
     * chdirへのAlias
     *
     * @access      public
     * @param  string $dir
     * @return bool
     * @codeCoverageIgnore
     */
    public function chdir($dir)
    {
        return chdir($dir);
    }

    protected function exec($cmd, $quiet = false)
    {
        return $this->command->exec($cmd, $quiet);
    }

    protected function createCmd($git_task, array $options = [])
    {
        $cmd = 'git ' . $git_task;
        if (count($options)) {
            $cmd .= ' ' . join(' ', $options);
        }

        return $cmd;
    }
}
