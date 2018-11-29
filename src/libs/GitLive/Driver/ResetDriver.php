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
class ResetDriver extends DriverBase
{
    /**
     * @param $remote
     * @throws Exception
     * @return string
     */
    public function forcePull($remote)
    {
        switch ($remote) {
            case 'upstream':
                return $this->upstream();

                break;
            case 'deploy':
                return $this->deploy();

                break;
            case 'origin':
                return $this->origin();

                break;
            default:
                throw new Exception(__('Undefined remote option : ') . $remote . ' You can use origin upstream deploy');
        }
    }

    /**
     *  upstream からfetchする
     *
     * @access      public
     * @throws Exception
     * @return string
     */
    public function upstream()
    {
        $status = $this->GitCmdExecutor->status();

        if (!strpos($status, 'nothing to commit, working tree clean')) {
            throw new Exception(__('Please clean or commit.') . "\n" . $status);
        }

        $this->Driver(FetchDriver::class)->upstream();
        $this->GitCmdExecutor->reset(['--hard', 'upstream/' . $this->getSelfBranch()]);

        return '';
    }

    /**
     *  deploy からfetchする
     *
     * @access      public
     * @throws Exception
     * @return string
     */
    public function origin()
    {
        $status = $this->GitCmdExecutor->status();

        if (!strpos($status, 'nothing to commit, working tree clean')) {
            throw new Exception(__('Please clean or commit.') . "\n" . $status);
        }

        $this->Driver(FetchDriver::class)->origin();
        $this->GitCmdExecutor->reset(['--hard', 'origin/' . $this->getSelfBranch()]);

        return '';
    }

    /**
     *  deploy からfetchする
     *
     * @access      public
     * @throws Exception
     * @return string
     */
    public function deploy()
    {
        $status = $this->GitCmdExecutor->status();

        if (!strpos($status, 'nothing to commit, working tree clean')) {
            throw new Exception(__('Please clean or commit.') . "\n" . $status);
        }

        $this->Driver(FetchDriver::class)->deploy();
        $this->GitCmdExecutor->reset(['--hard', $this->Driver(ConfigDriver::class)->deployRemote() . '/' . $this->getSelfBranch()]);

        return '';
    }
}
