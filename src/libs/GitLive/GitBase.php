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
namespace GitLive;

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
class GitBase
{
    protected $deploy_repository_name = 'deploy';
    const VERSION                     = '0.1.5';

    /**
     * +--
     *
     * @access      public
     * @param  var_text $text
     * @param  var_text $color OPTIONAL:null
     * @return void
     */
    public function debug($text, $color = null)
    {
        if (!$this->isDebug()) {
            return;
        }

        if ($color === null) {
            $this->ncecho($text);

            return;
        }

        $this->cecho($text, $color);
    }

    /* ----------------------------------------- */

    /**
     * +-- chdirへのAlias
     *
     * @access      public
     * @param  string $dir
     * @return bool
     * @codeCoverageIgnore
     */
    public function chdir($dir)
    {
        return chdir($dir);
    }
    /* ----------------------------------------- */

    /**
     * +-- 対話シェル
     *
     * @access      public
     * @param  var_text    $shell_message
     * @param  bool|string $using_default OPTIONAL:false
     * @return string
     * @codeCoverageIgnore
     */
    public function interactiveShell($shell_message, $using_default = false)
    {
        if (is_array($shell_message)) {
            $shell_message = join("\n", $shell_message);
        }
        $shell_message .= "\n";
        while (true) {
            $this->ncecho($shell_message);
            $this->ncecho(':');
            $res = trim(fgets(STDIN, 1000));
            if ($res === '') {
                if ($using_default === false) {
                    continue;
                }
                $res = $using_default;
            }

            break;
        }
        return $res;
    }
    /* ----------------------------------------- */

    /**
     * +-- Commandの実行
     *
     * @access      public
     * @param  string $cmd
     * @return string
     * @codeCoverageIgnore
     */
    public function exec($cmd)
    {
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);
        return $res;
    }
    /* ----------------------------------------- */

    /**
     * +-- デバッグモードかどうか
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isDebug()
    {
        global $is_debug;
        return $is_debug;
    }
    /* ----------------------------------------- */


    /**
     * +-- Windowsかどうか
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isWin()
    {
        return DIRECTORY_SEPARATOR === '\\';
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
        if ($this->isWin()) {
            $this->ncecho($text);

            return;
        }

        $cmd = 'echo -e "\e[3'.$color.'m'.escapeshellarg($text).'\e[m"';
        echo `$cmd`;
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
        if ($this->isWin()) {
            $text = mb_convert_encoding($text, 'SJIS-win', 'utf8');
        }

        echo $text;
    }

    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @param  string $src
     * @param  string $contents
     * @return bool
     * @codeCoverageIgnore
     */
    public function file_put_contents($src, $contents)
    {
        return file_put_contents($src, $contents);
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @param  string $src
     * @return bool
     * @codeCoverageIgnore
     */
    public function file_get_contents($src)
    {
        return file_get_contents($src);
    }
    /* ----------------------------------------- */
}
