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
namespace GitLive\Compile\Iterator;

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
class CompileFilterIterator extends \RecursiveFilterIterator
{
    public function accept()
    {
        $iterator = $this->getInnerIterator();
        if ($iterator->isDir()) {
            return true;
        }

        if (1 === preg_match('/\.php$/', $iterator->current())) {
            return true;
        } elseif (1 === preg_match('/\.po$/', $iterator->current())) {
            return true;
        }

        return false;
    }
}
