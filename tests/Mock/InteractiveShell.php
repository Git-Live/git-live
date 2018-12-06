<?php
/**
 * InteractiveShell.php
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
 * @since      2018-12-05
 */

namespace GitLive\Mock;


use GitLive\Support\InteractiveShellInterface;

class InteractiveShell implements InteractiveShellInterface
{

    /**
     * @param string $text
     * @return void
     */
    public function echo($text)
    {
        // TODO: Implement echo() method.
    }

    /**
     * @access      public
     * @param  array|string $shell_message
     * @param  bool|string  $using_default OPTIONAL:false
     * @return string
     */
    public function interactiveShell($shell_message, $using_default = false)
    {
        // TODO: Implement interactiveShell() method.
    }
}