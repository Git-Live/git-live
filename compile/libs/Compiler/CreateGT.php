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
class CreateGT
{
    /**
     * +-- コンストラクタ
     *
     * @access      public
     * @return void
     */
    public function __construct()
    {
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function execute()
    {
        foreach ($this->getFileList(BASE_DIR.'/src') as $po_file) {
            $res = $this->parsePO($po_file);
            file_put_contents($po_file.'.php', "<?php\n\nreturn ".var_export($res, true).';');
        }
    }
    /* ----------------------------------------- */

    protected function parsePO($po_file) {
        $is_msgid = false;
        $is_msgstr = false;
        $msgid = '';
        $res = array();
        foreach (file($po_file) as $data) {
            $data = trim($data);
            $data = mb_ereg_replace('#.*', '', $data);
            if (empty($data)) {
                continue;
            }
            if (strpos($data, 'msgid ') === 0) {
                $is_msgid = true;
                $is_msgstr = false;

                if (!empty($msgid) && empty($res[$msgid] )) {
                    $res[$msgid] = $msgid;
                }

                $msgid = '';
            }
            if (strpos($data, 'msgstr ') === 0) {
                $is_msgid = true;
                $is_msgstr = false;
                if (!empty($msgid)) {
                    $res[$msgid] = '';
                }
            }
            if (mb_ereg('"([^"]+)"', $data, $match)) {
                if ($is_msgid) {
                    $msgid .= $match[1];
                } elseif (!empty($msgid) && $is_msgstr) {
                    $res[$msgid] .= $match[1];
                }
            }
        }

        if (!empty($msgid) && empty($res[$msgid] )) {
            $res[$msgid] = $msgid;
        }
        return $res;
    }

    /**
     * +--
     *
     * @access      protected
     * @param  var_text $dir
     * @return array
     */
    protected function getFileList($dir)
    {
        $iterator = new \RecursiveDirectoryIterator($dir);
        $iterator = new \GitLive\Compile\Iterator\GetTextFilterIterator($iterator);
        $iterator = new \RecursiveIteratorIterator($iterator);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile() && mb_ereg('.po$', $fileinfo->getPathname())) {
                yield $fileinfo->getPathname();
            }
        }
    }
    /* ----------------------------------------- */
}
