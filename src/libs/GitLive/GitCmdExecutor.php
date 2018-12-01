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
class GitCmdExecutor
{
    protected $command;

    /**
     * GitCmdExecutor constructor.
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

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function config(array $options = [], $verbosity = true,  $output_verbosity = null)
    {
        $cmd = $this->createCmd('config', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function tag(array $options = [], $verbosity = false,  $output_verbosity = null)
    {
        $cmd = $this->createCmd('tag', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function copy(array $options = [], $verbosity = false,  $output_verbosity = null)
    {
        $cmd = $this->createCmd('clone', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function remote(array $options = [], $verbosity = false,  $output_verbosity = null)
    {
        $cmd = $this->createCmd('remote', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function status(array $options = [], $verbosity = false,  $output_verbosity = null)
    {
        $cmd = $this->createCmd('status', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function diff(array $options = [], $verbosity = false,  $output_verbosity = null)
    {
        $cmd = $this->createCmd('diff', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function merge($branch, array $options = [], $verbosity = false,  $output_verbosity = null)
    {
        $cmd = $this->createCmd('merge', $options);
        $cmd .= ' ' . $branch;

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function fetch(array $options = [], $verbosity = false,  $output_verbosity = null)
    {
        $cmd = $this->createCmd('fetch', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function clean(array $options = [], $verbosity = false,  $output_verbosity = null)
    {
        $cmd = 'git clean -df';
        if ($options) {
            $cmd = $this->createCmd('clean', $options);
        }

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function reset(array $options = [], $verbosity = false,  $output_verbosity = null)
    {
        $cmd = 'git reset --hard HEAD';
        if ($options) {
            $cmd = $this->createCmd('reset', $options);
        }

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function checkout($branch, array $options = [], $verbosity = false,  $output_verbosity = null)
    {
        $cmd = $this->createCmd('checkout', $options);
        $cmd .= ' ' . $branch;

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function branch(array $options = [], $verbosity = false,  $output_verbosity = null)
    {
        $cmd = $this->createCmd('branch', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function pull($remote, $branch = '', $verbosity = false,  $output_verbosity = null)
    {
        $cmd = $this->createCmd('pull', [$remote, $branch]);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function push($remote, $branch = '', $verbosity = false,  $output_verbosity = null)
    {
        $cmd = $this->createCmd('push', [$remote, $branch]);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function tagPush($remote, $verbosity = false,  $output_verbosity = null)
    {
        $cmd = $this->createCmd('push', [$remote, '--tags']);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    public function log($left, $right, $option = [], $verbosity = true,  $output_verbosity = null)
    {
        if (empty($option)) {
            $cmd = $this->createCmd('log', ['--pretty=fuller', '--name-status', $left . '..' . $right]);
        } else {
            $option[] = $left . '..' . $right;
            $cmd = $this->createCmd('log', ['--pretty=fuller', '--name-status'] + $option);
        }

        return $this->exec($cmd, true, $verbosity, $output_verbosity);
    }

    public function stash(array $options = [])
    {
        $cmd = $this->createCmd('stash', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
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

    protected function exec($cmd, $verbosity = false,  $output_verbosity = null)
    {
        return $this->command->exec($cmd, $verbosity, $output_verbosity);
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
