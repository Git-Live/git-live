<?php
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
class FetchDriver extends DriverBase
{
    /**
     *  upstream からfetchする
     *
     *
     * @access      public
     * @return void
     */
    public function upstream()
    {
        $this->GitCmdExecuter->fetch(['upstream']);
        $this->GitCmdExecuter->fetch(['-p', 'upstream']);
    }


    /**
     *  deploy からfetchする
     *
     *
     * @access      public
     * @param string $remove
     * @return void
     */
    public function deploy($remove = 'deploy')
    {
        $this->GitCmdExecuter->fetch([$remove]);
        $this->GitCmdExecuter->fetch(['-p', $remove]);
    }


    /**
     *  --allでフェッチする
     *
     * @access      public
     * @return void
     */
    public function all()
    {
        $this->GitCmdExecuter->fetch(['--all']);
        $this->GitCmdExecuter->fetch(['-p']);
    }

}
