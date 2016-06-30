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
class DriverBase extends \GitLive\GitBase
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


    /**
     * +-- 引数配列を返す
     *
     * 単体テストを楽にするために、処理を上書きして委譲する
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
     * +-- 色つきecho
     *
     * 単体テストを楽にするために、処理を上書きして委譲する
     *
     * @access      public
     * @param  var_text $text
     * @param  var_text $color
     * @return void
     * @codeCoverageIgnore
     */
    public function cecho($text, $color)
    {
        return $this->GitLive->cecho($text, $color);
    }

    /* ----------------------------------------- */


    /**
     * +-- 色なしecho
     *
     * 単体テストを楽にするために、処理を上書きして委譲する
     *
     * @access      public
     * @param  var_text $text
     * @return void
     * @codeCoverageIgnore
     */
    public function ncecho($text)
    {
        return $this->GitLive->ncecho($text);
    }

    /* ----------------------------------------- */



    /**
     * +-- 対話シェル
     *
     * @access      public
     * @param       var_text $shell_message
     * @param       bool|string $using_default OPTIONAL:false
     * @return      string
     */
    public function interactiveShell($shell_message, $using_default = false)
    {
        return $this->GitLive->interactiveShell($shell_message, $using_default);
    }
    /* ----------------------------------------- */


    /**
     * +-- デバッグメッセージ
     *
     * 単体テストを楽にするために、処理を上書きして委譲する
     *
     * @access      public
     * @param  var_text $text
     * @param  var_text $color OPTIONAL:null
     * @return void
     */
    public function debug($text, $color = null)
    {
        return $this->GitLive->debug($text, $color);
    }

    /* ----------------------------------------- */

    /**
     * +-- Commandの実行
     *
     * 単体テストを楽にするために、処理を上書きして委譲する
     *
     * @access      public
     * @param  string $cmd
     * @return string
     * @codeCoverageIgnore
     */
    public function exec($cmd)
    {
        return $this->GitLive->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- デバッグモードかどうか
     *
     * 単体テストを楽にするために、処理を上書きして委譲する
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isDebug()
    {
        return $this->GitLive->isDebug();
    }
    /* ----------------------------------------- */


    /**
     * +-- Windowsかどうか
     *
     * 単体テストを楽にするために、処理を上書きして委譲する
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isWin()
    {
        return $this->GitLive->isWin();
    }
    /* ----------------------------------------- */
}
