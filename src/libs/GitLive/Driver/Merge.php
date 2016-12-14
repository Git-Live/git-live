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
            case 'state':
                if (!isset($argv[3])) {
                    $this->Driver('Help')->help();
                    return;
                }
                switch ($argv[3]) {
                    case 'develop':
                        $this->stateDevelop();
                    break;
                    case 'master':
                        $this->stateMaster();
                    break;
                    default:
                        $this->Driver('Help')->help();
                    break;
                }
            break;
            default:
                $this->Driver('Help')->help();
            break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- developマージの事前確認
     *
     * @access      public
     * @return      void
     */
    public function stateDevelop()
    {
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        $res = $this->patchApplyCheck('upstream/develop');
        if ($res) {
            $this->ncecho("merge develop is not Conflict.\n");
            return;
        }
        $this->ncecho("merge develop is Conflict.\n");
    }
    /* ----------------------------------------- */

    /**
     * +-- masterマージの事前確認
     *
     * @access      public
     * @return      void
     */
    public function stateMaster()
    {
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        $res = $this->patchApplyCheck('upstream/master');
        if ($res) {
            $this->ncecho("merge develop is not Conflict.\n");
            return;
        }
        $this->ncecho("merge develop is Conflict.\n");
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
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
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
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        $this->GitCmdExecuter->merge('upstream/master');
    }
    /* ----------------------------------------- */
}
