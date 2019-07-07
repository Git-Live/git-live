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

namespace GitLive\Support;

use GitLive\GitBase;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GitCmdExecutor
 *
 * @category   GitCommand
 * @package    GitLive\Support
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-16
 */
class GitCmdExecutor extends GitBase
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
     * @param bool $verbosity
     * @param null $output_verbosity
     * @return string|null
     */
    public function fetchPullRequest($verbosity = true, $output_verbosity = null)
    {
        $cmd = "git fetch upstream '+refs/pull/*:refs/remotes/pr/*'";

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $cmd
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    protected function exec($cmd, $verbosity = false, $output_verbosity = null)
    {
        return $this->command->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function config($options = [], $verbosity = true, $output_verbosity = null)
    {
        $cmd = $this->createCmd('config', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $git_task
     * @param array|Collection $options
     * @return string
     */
    protected function createCmd(string $git_task, $options = []): string
    {
        $cmd = 'git ' . $git_task;
        $options = collect($options);
        if ($options->count()) {
            $cmd .= ' ' . $options->implode(' ');
        }

        return $cmd;
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function tag($options = [], $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('tag', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function clone($options = [], $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('clone', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function remote($options = [], $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('remote', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function status($options = [], $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('status', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function diff($options = [], $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('diff', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $branch
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function merge($branch, $options = [], $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('merge', $options);
        $cmd .= ' ' . $branch;

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function fetch($options = [], $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('fetch', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function clean($options = [], $verbosity = false, $output_verbosity = null)
    {
        $cmd = 'git clean -df';
        if ($options) {
            $cmd = $this->createCmd('clean', $options);
        }

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function reset($options = [], $verbosity = false, $output_verbosity = null)
    {
        $cmd = 'git reset --hard HEAD';
        if ($options) {
            $cmd = $this->createCmd('reset', $options);
        }

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $branch
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function checkout(string $branch, $options = [], $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('checkout', $options);
        $cmd .= ' ' . $branch;

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function branch($options = [], $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('branch', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $remote
     * @param string $branch
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function pull($remote, $branch = '', $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('pull', [$remote, $branch]);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param             $remote
     * @param string $branch
     * @param bool|string $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function push($remote, $branch = '', $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('push', [$remote, $branch]);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $remote
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function tagPush($remote, $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('push', [$remote, '--tags']);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $remote
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function tagPull($remote, $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('pull', [$remote, '--tags']);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param           string $left
     * @param           string $right
     * @param array|Collection $option
     * @param bool $without_common_commit
     * @param null|bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function log(string $left, string $right, $option = [], $without_common_commit = false, $verbosity = true, $output_verbosity = null)
    {
        $option[] = $left . ($without_common_commit ? '...' : '..') . $right;

        $cmd = $this->createCmd('log', $option);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function stash($options = [], $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('stash', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return string|null
     */
    public function add($options = [], $verbosity = false, $output_verbosity = null)
    {
        $cmd = $this->createCmd('add', $options);

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
    public function chdir($dir): bool
    {
        return chdir($dir);
    }

    /**
     * @return string
     */
    public function topLevelDir(): string
    {
        $cmd = 'git rev-parse --show-toplevel';

        return trim($this->exec($cmd, OutputInterface::VERBOSITY_DEBUG, OutputInterface::VERBOSITY_DEBUG));
    }

    /**
     * @param string $message
     */
    public function commit(string $message)
    {
        $message = trim($message);
        if ($message === '') {
            $message = date('YmdHis') . ' git live commit';
        }

        $message = str_replace('"', '\\"', $message);
        $cmd = 'git commit -m "' . $message . '" --no-verify';
        $this->command->exec($cmd, $verbosity, $output_verbosity);
    }
}
