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
class Log extends DriverBase
{

    /**
     * +-- mergeを実行する
     *
     * @access      public
     * @return void
     */
    public function log()
    {
        $argv = $this->getArgv();
        if (!isset($argv[2])) {
            $this->Driver('Help')->help();

            return;
        }

        switch ($argv[2]) {
            case 'develop':
                $this->logDevelop();
            break;
            case 'master':
                $this->logMaster();
            break;
            default:
                $this->Driver('Help')->help();
            break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- developとの差分をみる
     *
     * @access      public
     * @return void
     */
    public function logDevelop()
    {
        $this->Driver('Fetch')->all();
        $repository = $this->getSelfBranchRef();
        $this->ncecho($this->GitCmdExecuter->log('upstream/develop', $repository, array('--left-right')));
    }
    /* ----------------------------------------- */

    /**
     * +-- masterとの差分を見る
     *
     * @access      public
     * @return void
     */
    public function logMaster()
    {
        $this->Driver('Fetch')->all();
        $repository = $this->getSelfBranchRef();
        $this->ncecho($this->GitCmdExecuter->log('upstream/master', $repository, array('--left-right')));
    }
    /* ----------------------------------------- */
}
