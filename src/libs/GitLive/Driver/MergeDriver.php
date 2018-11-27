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
class MergeDriver extends DriverBase
{
    /**
     *  developマージの事前確認
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
     * @param $branch
     * @throws Exception
     * @return string
     */
    public function state($branch)
    {
        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();

        $res = $this->patchApplyCheck($branch);

        if ($res) {
            return '';
        }

        return $this->patchApplyDiff($branch);
    }

    /**
     *  masterマージの事前確認
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
     *  developをマージする
     *
     * @access      public
     * @throws Exception
     * @return void
     */
    public function mergeDevelop()
    {
        $branch = 'upstream/' . $this->Driver(ConfigDriver::class)->develop();
        $this->merge($branch);
    }

    /**
     * @param $branch
     * @throws Exception
     */
    public function merge($branch)
    {
        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();

        $this->GitCmdExecuter->merge($branch);
    }

    /**
     *  masterをマージする
     *
     * @access      public
     * @throws Exception
     * @return void
     */
    public function mergeMaster()
    {
        $branch = 'upstream/' . $this->Driver(ConfigDriver::class)->master();
        $this->merge($branch);
    }
}
