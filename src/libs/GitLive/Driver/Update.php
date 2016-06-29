<?php
/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
namespace GitLive\Driver;

/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
class Update extends DriverBase
{

    /**
     * +-- コマンドのアップデート
     *
     * @access      public
     * @return void
     */
    public function update()
    {
        $url = 'https://raw.githubusercontent.com/Git-Live/git-live/master/bin/git-live.phar';
        $this->GitLive->file_put_contents(GIT_LIVE_INSTALL_DIR, $this->GitLive->file_get_contents($url));
    }

    /* ----------------------------------------- */
}
