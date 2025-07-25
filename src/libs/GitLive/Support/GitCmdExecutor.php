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
    protected SystemCommandInterface $command;

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
     * @return null|string
     */
    public function fetchPullRequest(bool $verbosity = true, $output_verbosity = null): ?string
    {
        $cmd = "git fetch upstream '+refs/pull/*:refs/remotes/pr/*'";

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @return bool
     */
    public function isGitInit(): bool
    {
        return !$this->command->isError('git rev-parse --git-dir 2>&1');
    }

    /**
     * @return bool
     */
    public function isCleanWorkingTree(): bool
    {
        return !$this->command->isError('git diff --no-ext-diff --ignore-submodules --quiet --exit-code') &&
            !$this->command->isError('git diff-index --cached --quiet --ignore-submodules HEAD --');
    }

    /**
     * @return bool
     */
    public function isHeadless(): bool
    {
        return $this->command->isError('git rev-parse --quiet --verify HEAD  2>&1');
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function config($options = [], bool $verbosity = true, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('config', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function tag($options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('tag', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function clone($options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('clone', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function remote($options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('remote', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function status($options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('status', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function diff($options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('diff', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $branch
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function merge(string $branch, $options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('merge', $options);
        $cmd .= ' ' . $branch;

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function fetch($options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('fetch', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function clean($options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = 'git clean -df';
        if ($options) {
            $cmd = $this->createCmd('clean', array_merge(['-df'], $options));
        }

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function reset($options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
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
     * @return null|string
     */
    public function checkout(string $branch, $options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('checkout', $options);
        $cmd .= ' ' . $branch;

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function branch($options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('branch', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $remote
     * @param string $branch
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function pull(string $remote, string $branch = '', bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('pull', [$remote, $branch]);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $remote
     * @param string $branch
     * @param array|\ArrayAccess $option
     * @param bool|string $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function push(string $remote, string $branch = '', $option = [], $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $option = collect($option);
        $option->push($remote);
        $option->push($branch);
        $cmd = $this->createCmd('push', $option);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $remote
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function tagPush(string $remote, bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('push', [$remote, '--tags']);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $remote
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function tagPull(string $remote, bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('pull', [$remote, '--tags']);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $left
     * @param string $right
     * @param array|Collection $option
     * @param bool $without_common_commit
     * @param null|bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function log(string $left, string $right, $option = [], bool $without_common_commit = false, ?bool $verbosity = true, ?bool $output_verbosity = null): ?string
    {
        $option[] = $left . ($without_common_commit ? '...' : '..') . $right;

        $cmd = $this->createCmd('log', $option);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function stash($options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('stash', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function add($options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('add', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param array|Collection $options
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    public function init($options = [], bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        $cmd = $this->createCmd('init', $options);

        return $this->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * chdirへのAlias
     *
     * @access      public
     * @param string $dir
     * @return bool
     * @codeCoverageIgnore
     */
    public function chdir(string $dir): bool
    {
        return chdir($dir);
    }

    /**
     * @return string
     */
    public function topLevelDir(): string
    {
        $cmd = 'git rev-parse --show-toplevel';

        return trim((string)$this->exec($cmd, OutputInterface::VERBOSITY_DEBUG, OutputInterface::VERBOSITY_DEBUG));
    }

    /**
     * @param string $message
     */
    public function commit(string $message): void
    {
        $message = trim($message);
        if ($message === '') {
            $message = date('YmdHis') . ' git live commit';
        }

        $message = str_replace('"', '\\"', $message);
        $cmd = 'git commit -m "' . $message . '" --no-verify';
        $verbosity = 0;
        $output_verbosity = null;
        $this->command->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * @param string $cmd
     * @param bool $verbosity
     * @param null|bool $output_verbosity
     * @return null|string
     */
    protected function exec(string $cmd, bool $verbosity = false, ?bool $output_verbosity = null): ?string
    {
        return $this->command->exec($cmd, $verbosity, $output_verbosity);
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
}
