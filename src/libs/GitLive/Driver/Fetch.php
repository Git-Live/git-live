<?php
/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
namespace GitLive\Driver;

/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
class Fetch extends DriverBase
{


    /**
     * +-- upstream からfetchする
     *
     *
     * @access      public
     * @return void
     */
    public function upstream()
    {
        $this->GitCmdExecuter->fetch(array('upstream'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
    }
    /* ----------------------------------------- */


    /**
     * +-- deploy からfetchする
     *
     *
     * @access      public
     * @return void
     */
    public function deploy()
    {
        $this->GitCmdExecuter->fetch(array('deploy'));
        $this->GitCmdExecuter->fetch(array('-p', 'deploy'));
    }
    /* ----------------------------------------- */


    /**
     * +-- --allでフェッチする
     *
     * @access      public
     * @return void
     */
    public function all()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
    }
    /* ----------------------------------------- */

}
