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
namespace GitLive\Mock;

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
class GitLive extends \GitLive\GitLive
{
    /**
     * +-- コンストラクタ
     *
     * @access      public
     * @return void
     */
    public function __construct()
    {
        class_exists('\GitLive\Mock\GitCmdExecuter');
        $this->GitCmdExecuter = \EnviMockLight::mock('\GitLive\Mock\GitCmdExecuter', array(), false);
        $this->GitCmdExecuter->shouldReceive('exec')
        ->andNoBypass();
    }

    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @param  string                     $driver_name
     * @return \GitLive\Driver\DriverBase
     */
    public function Driver($driver_name)
    {
        if (!isset($this->Driver[$driver_name])) {
            $class_name                 = '\GitLive\Driver'.'\\'.$driver_name;
            $this->Driver[$driver_name] = new $class_name($this);
        }

        return $this->Driver[$driver_name];
    }
    /* ----------------------------------------- */

    /**
     * +-- 色つきecho
     *
     * @access      public
     * @param  var_text $text
     * @param  var_text $color
     * @return void
     * @codeCoverageIgnore
     */
    public function cecho($text, $color)
    {
        echo $text;
    }

    /* ----------------------------------------- */

    /**
     * +-- 色なしecho
     *
     * @access      public
     * @param  var_text $text
     * @return void
     * @codeCoverageIgnore
     */
    public function ncecho($text)
    {
        echo $text;
    }

    /* ----------------------------------------- */
}

/* ----------------------------------------- */
