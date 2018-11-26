<?php
/**
 * Envelopment.php
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
class Envelopment
{
    /**
     * Windowsかどうか
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isWin()
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
    public function isDebug()
    {
        return $this->getEnv('APP_ENV', 'production') === 'production';
    }

    /**
     * @param string $key
     * @param        $default_value
     * @return array|string|null
     */
    public function getEnv($key, $default_value = null)
    {
        $res = getenv($key);

        return $res === false ? $default_value : $res;
    }


    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function putEnv($key, $value)
    {
        return putenv($key . '=' . var_export($value, true));
    }

}