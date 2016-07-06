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
class Merge extends DriverBase
{

    /**
     * +-- mergeを実行する
     *
     * @access      public
     * @return void
     */
    public function merge()
    {
        $argv = $this->getArgv();
        if (!isset($argv[2])) {
            $this->Driver('Help')->help();

            return;
        }

        switch ($argv[2]) {
            case 'develop':
                $this->mergeDevelop();
            break;
            case 'master':
                $this->mergeMaster();
            break;
            default:
                $this->Driver('Help')->help();
            break;
        }
    }

    /* ----------------------------------------- */

    /**
     * +-- developをマージする
     *
     * @access      public
     * @return void
     */
    public function mergeDevelop()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $this->GitCmdExecuter->merge('upstream/develop');
    }

    /* ----------------------------------------- */

    /**
     * +-- masterをマージする
     *
     * @access      public
     * @return void
     */
    public function mergeMaster()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $this->GitCmdExecuter->merge('upstream/master');
    }

    /* ----------------------------------------- */
}
