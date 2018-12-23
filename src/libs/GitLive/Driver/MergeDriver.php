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
     * @throws Exception
     * @return string
     */
    public function stateDevelop()
    {
        $branch = 'upstream/' . $this->Driver(ConfigDriver::class)->develop();

        return $this->state($branch);
    }

    /**
     *  Prior confirmation for $branch merging
     *
     * @param $branch
     * @throws Exception
     * @return string
     */
    public function state($branch)
    {
        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();

        return $this->patchApplyDiff($branch);
    }

    /**
     *  Prior confirmation for master merging
     *
     * @access      public
     * @throws Exception
     * @return string
     */
    public function stateMaster()
    {
        $branch = 'upstream/' . $this->Driver(ConfigDriver::class)->master();

        return $this->state($branch);
    }

    /**
     *  Merge a develop branch.
     *
     * @access      public
     * @throws Exception
     * @return string
     */
    public function mergeDevelop()
    {
        $branch = 'upstream/' . $this->Driver(ConfigDriver::class)->develop();

        return $this->merge($branch);
    }

    /**
     * fetch and merge.
     *
     * @param $branch
     * @throws Exception
     * @return string
     */
    public function merge($branch)
    {
        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();

        return $this->GitCmdExecutor->merge($branch);
    }

    /**
     *  Merge a master branch.
     *
     * @access      public
     * @throws Exception
     * @return string
     */
    public function mergeMaster()
    {
        $branch = 'upstream/' . $this->Driver(ConfigDriver::class)->master();

        return $this->merge($branch);
    }
}
