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

use GitLive\Command\PullCommand;

/**
 * Class ResetDriver
 *
 * Operations like git reset command
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
class ResetDriver extends DriverBase
{
    /**
     * git live pull --force
     *
     * @param $remote
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return string
     * @see PullCommand
     */
    public function forcePull($remote): ?string
    {
        switch ($remote) {
            case 'upstream':
                return $this->upstream();
            case 'deploy':
                return $this->deploy();
            case 'origin':
                return $this->origin();
            default:
                throw new Exception(__('Undefined remote option.') . ' : ' . $remote . ' You can use origin upstream deploy');
        }
    }

    /**
     *  fetch from upstream
     *
     * @access      public
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return string
     */
    public function upstream(): string
    {
        $this->isCleanOrFail();

        $this->Driver(FetchDriver::class)->upstream();
        $this->GitCmdExecutor->reset(['--hard', 'upstream/' . $this->getSelfBranch()]);

        return '';
    }

    /**
     *  fetch from origin
     *
     * @access      public
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return string
     */
    public function origin(): string
    {
        $this->isCleanOrFail();

        $this->Driver(FetchDriver::class)->origin();
        $this->GitCmdExecutor->reset(['--hard', 'origin/' . $this->getSelfBranch()]);

        return '';
    }

    /**
     *  fetch from deploy
     *
     * @access      public
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return string
     */
    public function deploy(): string
    {
        $this->isCleanOrFail();

        $this->Driver(FetchDriver::class)->deploy();
        $this->GitCmdExecutor->reset(['--hard', $this->Driver(ConfigDriver::class)->deployRemote() . '/' . $this->getSelfBranch()]);

        return '';
    }
}
