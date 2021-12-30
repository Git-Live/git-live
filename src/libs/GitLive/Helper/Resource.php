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

namespace GitLive\Helper;

use GitLive\GitBase;

/**
 * Class Resource
 *
 * @category   GitCommand
 * @package    GitLive\Hepler
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2021/12/30
 */
class Resource extends GitBase
{
    /**
     * @param string $file
     * @param string $default
     * @return string
     */
    public function get(string $file, string $default = ''): string
    {
        $file_path = RESOURCES_DIR . DIRECTORY_SEPARATOR . $file;

        return is_file($file_path) ? file_get_contents($file_path) : $default;
    }

    /**
     * @param string $file
     * @param string $default
     * @return string
     */
    public function help(string $file, string $default = ''): string
    {
        $help_path = RESOURCES_DIR . DIRECTORY_SEPARATOR . 'help' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR;
        if (is_file($help_path . GIT_LIVE_LANG . DIRECTORY_SEPARATOR . $file)) {
            return file_get_contents($help_path . GIT_LIVE_LANG . DIRECTORY_SEPARATOR . $file);
        }
        if (is_file($help_path . 'en_US' . DIRECTORY_SEPARATOR . $file)) {
            return file_get_contents($help_path . 'en_US' . DIRECTORY_SEPARATOR . $file);
        }

        return $default;
    }
}
