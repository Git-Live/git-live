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
 * Class FetchDriver
 *
 * Operations like git feature command
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
class FetchDriver extends DriverBase
{
    /**
     *  upstream からfetchする
     *
     * @access      public
     * @return void
     */
    public function upstream()
    {
        $this->GitCmdExecutor->fetch(['upstream']);
        $this->GitCmdExecutor->fetch(['-p', 'upstream']);
    }

    /**
     *  origin からfetchする
     *
     * @access      public
     * @return void
     */
    public function origin()
    {
        $this->GitCmdExecutor->fetch(['origin']);
        $this->GitCmdExecutor->fetch(['-p', 'origin']);
    }

    /**
     *  deploy からfetchする
     *
     * @access      public
     * @param string|null $remote
     * @return void
     *@throws Exception
     */
    public function deploy(string $remote = null)
    {
        if ($remote === null) {
            $remote = $this->Driver(ConfigDriver::class)->deployRemote();
        }

        $this->GitCmdExecutor->fetch([$remote]);
        $this->GitCmdExecutor->fetch(['-p', $remote]);
    }

    /**
     *  --allでフェッチする
     *
     * @access      public
     * @return void
     */
    public function all()
    {
        $this->GitCmdExecutor->fetch(['--all']);
        $this->GitCmdExecutor->fetch(['-p']);
    }
}
