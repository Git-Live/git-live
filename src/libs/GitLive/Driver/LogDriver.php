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

use Symfony\Component\Console\Output\OutputInterface;

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
class LogDriver extends DriverBase
{
    /**
     *  developとの差分をみる
     *
     * @access      public
     * @throws Exception
     * @return string
     */
    public function logDevelop()
    {
        return $this->log($this->Driver(ConfigDriver::class)->develop());
    }

    /**
     *  masterとの差分を見る
     *
     * @access      public
     * @throws Exception
     * @return string
     */
    public function logMaster()
    {
        return $this->log($this->Driver(ConfigDriver::class)->master());
    }

    /**
     * @param string $from_branch
     * @throws Exception
     * @return string
     */
    public function log($from_branch)
    {
        $this->Driver(FetchDriver::class)->all();
        $to_branch = $this->getSelfBranchRef();

        return $this->GitCmdExecutor->log('upstream/' . $from_branch, $to_branch, ['--left-right'], false, OutputInterface::VERBOSITY_DEBUG);
    }
}
