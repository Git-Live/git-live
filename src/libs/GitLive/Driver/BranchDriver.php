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

use GitLive\Support\Collection;

/**
 * Class BranchDriver
 *
 * Operations like git branch command
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
class BranchDriver extends DriverBase
{
    /**
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return bool
     */
    public function hasMasterBranch(): bool
    {
        $Config = $this->Driver(ConfigDriver::class);

        return $this->isBranchExistsSimple($Config->master());
    }

    /**
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return bool
     */
    public function hasDevelopBranch(): bool
    {
        $Config = $this->Driver(ConfigDriver::class);

        return $this->isBranchExistsSimple($Config->develop());
    }

    /**
     * Get local branch collection.
     *
     * @return Collection
     */
    public function branchList(): Collection
    {
        $branch = $this->GitCmdExecutor->branch(['--no-color'], true);

        return $this->makeArray($branch);
    }

    /**
     * Get all branch collection.
     *
     * @return Collection
     */
    public function branchListAll(): Collection
    {
        $branch = $this->GitCmdExecutor->branch(['-a', '--no-color'], true);

        return $this->makeArray($branch);
    }

    /**
     * @return bool
     */
    public function localBranchExist(): bool
    {
        $res = $this->branchList();

        return count($res) !== 0;
    }

    /**
     * @return bool
     */
    public function remoteBranchExist(): bool
    {
        $res = $this->remoteBranchList();

        return count($res) !== 0;
    }

    /**
     * Get all branch collection.
     *
     * @return Collection
     */
    public function remoteBranchList(): Collection
    {
        $branch = $this->GitCmdExecutor->branch(['-r', '--no-color'], true);

        return $this->makeArray($branch);
    }

    /**
     * Has a branch.
     *
     * @param string $branch
     * @return bool
     */
    public function isBranchExistsAll(string $branch): bool
    {
        $branches = $this->branchListAll();
        if ($branches->search('remotes/origin/' . $branch) !== false) {
            return true;
        }
        if ($branches->search('remotes/upstream/' . $branch) !== false) {
            return true;
        }
        if ($branches->search($branch) !== false) {
            return true;
        }

        return false;
    }

    /**
     * @param $branch
     * @return bool
     */
    public function isBranchExistsSimple($branch): bool
    {
        $branches = $this->branchList();

        return $branches->has($branch);
    }

    /**
     * @param $branch
     * @return bool
     */
    public function isRemoteBranchExistsSimple($branch): bool
    {
        $branches = $this->remoteBranchList();

        return $branches->has($branch);
    }

    /**
     * Get a Collection from the text of the result of "git branch - list".
     *
     * @param string $branch
     * @return Collection
     */
    private function makeArray(string $branch): Collection
    {
        $branch_list = explode("\n", rtrim($branch));

        array_walk($branch_list, static function (&$item) {
            $pos = strpos($item, ' -> ') ?: null;
            $item = trim(mb_substr($item, 1, $pos));
        });

        return collect($branch_list);
    }
}
