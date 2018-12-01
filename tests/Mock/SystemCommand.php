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

use GitLive\Support\SystemCommandInterface;

class SystemCommand implements SystemCommandInterface
{
    /**
     * @param string   $cmd
     * @param bool|int $verbosity
     * @param null     $output_verbosity
     * @return string
     */
    public function exec($cmd, $verbosity = 0,  $output_verbosity = null)
    {
        dump($cmd);

        return $cmd;
    }
}
