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
class LogDriver extends DriverBase
{

    /**
     *  developとの差分をみる
     *
     * @access      public
     * @return string
     * @throws Exception
     */
    public function logDevelop()
    {
        return $this->log($this->Driver(ConfigDriver::class)->develop());
    }

    /**
     * @param string $from_branch
     * @return string
     * @throws Exception
     */
    public function log($from_branch)
    {
        $this->Driver(FetchDriver::class)->all();
        $to_branch = $this->getSelfBranchRef();

        return $this->GitCmdExecuter->log('upstream/' . $from_branch, $to_branch, '--left-right');

    }

    /**
     *  masterとの差分を見る
     *
     * @access      public
     * @return string
     * @throws Exception
     */
    public function logMaster()
    {
        return $this->log($this->Driver(ConfigDriver::class)->master());
    }

}
