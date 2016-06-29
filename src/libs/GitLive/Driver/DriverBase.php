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

use GitLive\GitBase;

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
class DriverBase extends GitBase
{
    protected $GitLive;
    protected $GitCmdExecuter;


    /**
     * +-- コンストラクタ
     *
     * @access      public
     * @param  var_text $GitLive
     * @return void
     * @codeCoverageIgnore
     */
    public function __construct($GitLive)
    {
        $this->GitLive        = $GitLive;
        $this->GitCmdExecuter = $GitLive->getGitCmdExecuter();
    }
    /* ----------------------------------------- */



    /**
     * +-- 引数配列を返す
     *
     * @access      public
     * @return array
     * @codeCoverageIgnore
     */
    public function getArgv()
    {
        return $this->GitLive->getArgv();
    }
    /* ----------------------------------------- */

    /**
     * +-- 今のブランチを取得する
     *
     * @access      public
     * @return string
     * @codeCoverageIgnore
     */
    public function getSelfBranch()
    {
        return $this->GitLive->getSelfBranch();
    }

    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @param  string                     $driver_name
     * @return \GitLive\Driver\DriverBase
     * @codeCoverageIgnore
     */
    public function Driver($driver_name)
    {
        return $this->GitLive->Driver($driver_name);
    }
    /* ----------------------------------------- */
}
