<?php
/**
 * SystemCommandInterface.php
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
 * @since      2018/11/23
 */

namespace GitLive\Support;


interface SystemCommandInterface
{

    /**
     * @param string   $cmd
     * @param bool|int $verbosity
     * @return string
     */
    public function exec($cmd, $verbosity = 0);

}