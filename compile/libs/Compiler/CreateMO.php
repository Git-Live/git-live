<?php
/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveCompile
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */

namespace GitLive\Compile\Compiler;

/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveCompile
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
class CreateMO
{
    /**
     * +-- コンストラクタ
     *
     * @access      public
     * @return      void
     */
    public function __construct()
    {
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return      void
     */
    public function execute()
    {
        foreach ($this->getCommandList(BASE_DIR.'/src') as $cmd) {
            `$cmd`;
        }
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      protected
     * @param       var_text $dir
     * @return      array
     */
    protected function getCommandList($dir)
    {
        $iterator = new \RecursiveDirectoryIterator($dir);
        $iterator = new \GitLive\Compile\Iterator\GetTextFilterIterator($iterator);
        $iterator = new \RecursiveIteratorIterator($iterator);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile()) {
                $lang = substr($fileinfo->getPathname(), 0, -3);
                unlink("{$lang}.mo");
                $cmd = "msgfmt {$lang}.po -o {$lang}.mo";
                yield $cmd;
            }
        }
    }
    /* ----------------------------------------- */
}
