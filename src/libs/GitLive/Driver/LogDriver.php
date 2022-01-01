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
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LogDriver
 *
 * Operations like git log command
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
class LogDriver extends DriverBase
{
    /**
     *  developとの差分をみる
     *
     * @access      public
     * @param array|Collection $option
     * @throws Exception
     * @return string
     */
    public function logDevelop($option = []): string
    {
        return $this->log($this->Driver(ConfigDriver::class)->develop(), $option);
    }

    /**
     *  masterとの差分を見る
     *
     * @access      public
     * @param array|Collection $option
     * @throws Exception
     * @return string
     */
    public function logMaster($option = []): string
    {
        return $this->log($this->Driver(ConfigDriver::class)->master(), $option);
    }

    /**
     * @param string $from_branch
     * @param array|Collection $option
     *@throws Exception
     * @return string
     */
    public function log(string $from_branch, $option = []): string
    {
        $this->Driver(FetchDriver::class)->all();
        $to_branch = $this->getSelfBranch();

        $option[] = '--left-right';

        return (string)$this->GitCmdExecutor->log(
            'upstream/' . $from_branch,
            $to_branch,
            $option,
            false,
            false,
            OutputInterface::VERBOSITY_DEBUG
        );
    }
}
