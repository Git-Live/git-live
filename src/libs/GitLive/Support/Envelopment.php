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

namespace GitLive\Support;

use GitLive\GitBase;

/**
 * Class Envelopment
 *
 * @category   GitCommand
 * @package    GitLive\Support
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
class Envelopment extends GitBase
{
    /**
     * Windowsかどうか
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isWin(): bool
    {
        return DIRECTORY_SEPARATOR === '\\';
    }

    /**
     *  デバッグモードかどうか
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isDebug(): bool
    {
        return $this->getEnv('APP_ENV', 'production') !== 'production';
    }

    /**
     * @param string $key
     * @param        $default_value
     * @return null|array|string
     */
    public function getEnv(string $key, $default_value = null)
    {
        $res = getenv($key);

        return $res === false ? $default_value : $res;
    }

    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function putEnv(string $key, string $value): bool
    {
        return putenv($key . '=' . $value);
    }
}
