<?php
/**
 * InteractiveShellInterface.php
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018/11/24
 */

namespace GitLive\Support;


interface InteractiveShellInterface
{
    /**
     * @param string $text
     * @return void
     */
    public function echo($text);

    /**
     * @access      public
     * @param  string|array $shell_message
     * @param  bool|string  $using_default OPTIONAL:false
     * @return string
     */
    public function interactiveShell($shell_message, $using_default = false);

}