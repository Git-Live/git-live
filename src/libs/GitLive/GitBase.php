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
    const VERSION                     = '0.1.7';

    /**
     * +-- 引数配列を返す
     *
     * @access      public
     * @return array
     * @codeCoverageIgnore
     */
    public function getArgv()
    {
        global $argv;
        return $argv;
    }
    /* ----------------------------------------- */

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
        $this->cecho($cmd, 6);
        $res = `$cmd`;
        $this->ncecho($res);
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
     * +-- quietモードかどうか
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isQuiet()
    {
        static $is_quiet;
        if (empty($is_quiet)) {
            $is_quiet = $this->isOption('-q') || $this->isOption('--quiet');
        }
        return $is_quiet;
    }
    /* ----------------------------------------- */

    /**
     * +-- 引数の取得
     *
     * 指定した引数の次の値(-f "filename"のfilename)
     * を取得します。<br />
     * 存在しない場合は、
     * $default_paramの値を返す。
     *
     * @access      public
     * @param  string $name
     * @param  mix    $default_param (optional:false)
     * @return mix
     */
    public function getOption($name, $default_param = false)
    {
        static $fargv;
        $argv = $this->getArgv();
        if (empty($fargv)) {
            $fargv = array_flip($argv);
        }

        if (isset($fargv[$name])) {
            $x = $fargv[$name] + 1;
            if (isset($argv[$x])) {
                return $argv[$x];
            }
        }
        foreach ($argv as $v) {
            if (strpos($v, $name.'=') !== false) {
                return mb_substr($v, strpos($v, '=') + 1);
            } elseif (strpos($v, $name.':') !== false) {
                return mb_substr($v, strpos($v, ':') + 1);
            }
        }
        return $default_param;
    }
    /* ----------------------------------------- */

    /**
     * +-- 引数の取得
     *
     * 指定した引数の次の値(-f "filename"のfilename)
     * を取得します。<br />
     * 存在しない場合は、
     * $default_paramの値を返す。
     *
     * @access      public
     * @param  string $name
     * @return array
     */
    public function getOptions($name)
    {
        $argv = $this->getArgv();

        $res = array();
        foreach ($argv as $k => $v) {
            if (strpos($v, $name.'=') !== false) {
                $res[] = mb_substr($v, strpos($v, '=') + 1);
            } elseif (strpos($v, $name.':') !== false) {
                $res[] = mb_substr($v, strpos($v, ':') + 1);
            } elseif ($v === $name) {
                $x = $k + 1;
                if (isset($argv[$x])) {
                    $res[] = $argv[$x];
                }
            }
        }
        return $res;
    }
    /* ----------------------------------------- */

    /**
     * +--$nameで指定された引数が存在するかどうかを確認する
     *
     * @access      public
     * @param  string $name
     * @return bool
     */
    public function isOption($name)
    {
        static $fargv;

        if (empty($fargv)) {
            $fargv = $this->getFargv();
        }

        return isset($fargv[$name]);
    }
    /* ----------------------------------------- */

    /**
     * +-- Fargvを返す
     *
     * @access      public
     * @return array
     */
    public function getFargv()
    {
        $argv = $this->getArgv();
        foreach ($argv as $k => $item) {
            if (strpos($item, '--') === 0 && $item !== '--') {
                if (strpos($item, '=')) {
                    list($item, ) = explode('=', $item, 2);
                } elseif (strpos($item, ':')) {
                    list($item, ) = explode(':', $item, 2);
                }
                $fargv[$item] = $k;
            } elseif (strpos($item, '-') === 0 && $item !== '--'  && $item !== '-') {
                $arr = str_split($item, 1);
                array_shift($arr);
                foreach ($arr as $item) {
                    $fargv['-'.$item] = $k;
                }
            } else {
                $fargv[$item] = $k;
            }
        }

        return $fargv;
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
        if ($this->isQuiet()) {
            return;
        }
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
        if ($this->isQuiet()) {
            return;
        }
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
