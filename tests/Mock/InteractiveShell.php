<?php

/**
 * This file is part of Git-Live
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id\$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 */

namespace GitLive\Mock;

use GitLive\Support\InteractiveShellInterface;

/**
 * Class InteractiveShell
 *
 * @category   GitCommand
 * @package    GitLive\Mock
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-13
 * @codeCoverageIgnore
 */
class InteractiveShell implements InteractiveShellInterface
{
    /**
     * @param string $text
     * @return void
     * @codeCoverageIgnore
     */
    public function echo($text): void
    {
        // TODO: Implement echo() method.
    }

    /**
     * @access      public
     * @param  array|string $shell_message
     * @param  bool|string  $using_default OPTIONAL:false
     * @return string
     */
    public function interactiveShell($shell_message, $using_default = false): string
    {
        // TODO: Implement interactiveShell() method.

        return '';
    }
}
