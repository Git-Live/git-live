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

use Phar;

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
class CreatePhar
{
    protected $phar;
    /**
     * +-- コンストラクタ
     *
     * @access      public
     * @return      void
     */
    public function __construct()
    {
        $this->phar = new Phar(BASE_DIR.'/bin/git-live.phar', 0);
        $this->phar->setSignatureAlgorithm(Phar::SHA256);
        $this->phar->setStub(file_get_contents(BASE_DIR.'/compile/src/stub.php'));
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
        $this->addFileList(BASE_DIR.'/src');
        $this->phar->stopBuffering();

        throw new \GitLive\Compile\Exception\Kill;
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      protected
     * @param       var_text $dir
     * @return      array
     */
    protected function addFileList($dir)
    {
        $iterator = new \RecursiveDirectoryIterator($dir);
        $iterator = new \GitLive\Compile\Iterator\CompileFilterIterator($iterator);
        $iterator = new \RecursiveIteratorIterator($iterator);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile()) {
                $this->phar->addFile(
                    $fileinfo->getPathname(),
                    strtr(
                        $fileinfo->getPathname(),
                        array(BASE_DIR.'/src' => '')
                    )
                );

            }
        }
    }
    /* ----------------------------------------- */
}
