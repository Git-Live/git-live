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
 * Class MergeDriver
 *
 * Operations like git merge command
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
class MergeDriver extends DriverBase
{
    /**
     *  Prior confirmation for develop merging
     *
     * @access      public
     * @throws \GitLive\Driver\Exception
     * @throws \ErrorException
     * @return string
     */
    public function stateDevelop(): string
    {
        $branch = 'upstream/' . $this->Driver(ConfigDriver::class)->develop();

        return $this->state($branch);
    }

    /**
     *  Prior confirmation for $branch merging
     *
     * @param $branch
     * @throws \GitLive\Driver\Exception
     * @throws \ErrorException
     * @return string
     */
    public function state($branch): string
    {
        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();

        return $this->patchApplyDiff($branch);
    }

    /**
     *  Prior confirmation for master merging
     *
     * @access      public
     * @throws \GitLive\Driver\Exception
     * @throws \ErrorException
     * @return string
     */
    public function stateMaster(): string
    {
        $branch = 'upstream/' . $this->Driver(ConfigDriver::class)->master();

        return $this->state($branch);
    }

    /**
     *  Merge a develop branch.
     *
     * @access      public
     * @throws \GitLive\Driver\Exception
     * @throws \ErrorException
     * @return null|string
     */
    public function mergeDevelop(): ?string
    {
        $branch = 'upstream/' . $this->Driver(ConfigDriver::class)->develop();

        return $this->merge($branch);
    }

    /**
     * fetch and merge.
     *
     * @param $branch
     * @throws \GitLive\Driver\Exception
     * @throws \ErrorException
     * @return null|string
     */
    public function merge($branch): ?string
    {
        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();

        return $this->GitCmdExecutor->merge($branch);
    }

    /**
     *
     * @access      public
     * @throws \GitLive\Driver\Exception
     * @throws \ErrorException
     * @return null|string
     */
    public function mergeMaster(): ?string
    {
        $branch = 'upstream/' . $this->Driver(ConfigDriver::class)->master();

        return $this->merge($branch);
    }

    /**
     *  Merge a other feature  branch.
     *
     * @param string $feature_name
     * @throws \GitLive\Driver\Exception
     * @throws \ErrorException
     * @return null|string
     */
    public function mergeFeature(string $feature_name): ?string
    {
        $Config = $this->Driver(ConfigDriver::class);
        $feature_prefix = (string)$Config->featurePrefix();

        $feature_branch = $feature_name;
        if ($feature_prefix !== '' && strpos($feature_name, $feature_prefix) !== 0) {
            $feature_branch = $feature_prefix . $feature_name;
        }

        $Fetch = $this->Driver(FetchDriver::class);

        $Fetch->all();

        $branch_list = $this->Driver(BranchDriver::class)->branchListAll();

        $branch = 'remotes/upstream/' . $feature_branch;
        if ($branch_list->search($branch) !== false) {
            return $this->merge($branch);
        }

        $branch = 'remotes/origin/' . $feature_branch;
        if ($branch_list->search($branch) !== false) {
            return $this->merge($branch);
        }

        return sprintf('Error:' . __('Feature name %s not found'), $feature_name);
    }
}
