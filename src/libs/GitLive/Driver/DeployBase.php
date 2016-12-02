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
class DeployBase extends DriverBase
{

    /**
     * +-- リリースが空いているかどうか
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isReleaseOpen()
    {
        return $this->GitLive->isReleaseOpen();
    }
    /* ----------------------------------------- */

    /**
     * +-- ホットフィクスが空いているかどうか
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isHotfixOpen()
    {
        return $this->GitLive->isHotfixOpen();
    }
    /* ----------------------------------------- */

    /**
     * +-- releaseコマンド、hotfixコマンドが使用できるかどうか
     *
     * @access      public
     * @return void
     * @codeCoverageIgnore
     */
    public function enableRelease()
    {
        return $this->GitLive->enableRelease();
    }
    /* ----------------------------------------- */

    /**
     * +-- 使用しているリリースRepositoryの取得
     *
     * @access      public
     * @return string
     * @codeCoverageIgnore
     */
    public function getReleaseRepository()
    {
        return $this->GitLive->getReleaseRepository();
    }
    /* ----------------------------------------- */

    /**
     * +-- 使用しているhot fix Repositoryの取得
     *
     * @access      public
     * @return string
     * @codeCoverageIgnore
     */
    public function getHotfixRepository()
    {
        return $this->GitLive->getHotfixRepository();
    }
    /* ----------------------------------------- */

    /**
     * +-- hotfixCloseとreleaseClose共通処理
     *
     * @access      public
     * @param  var_text $repo
     * @param  var_text $mode
     * @param  var_text $force OPTIONAL:false
     * @return void
     * @codeCoverageIgnore
     */
    public function deployEnd($repo, $mode, $force = false)
    {
        return $this->GitLive->deployEnd($repo, $mode, $force);
    }
    /* ----------------------------------------- */

    /**
     * +-- DeployブランチにSyncする
     *
     * @access      public
     * @param  var_text $repo
     * @return void
     * @codeCoverageIgnore
     */
    public function deploySync($repo)
    {
        return $this->GitLive->deploySync($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- upstream に pushする
     *
     * @access      public
     * @param  var_text $repo
     * @return void
     * @codeCoverageIgnore
     */
    public function deployPush($repo)
    {
        return $this->GitLive->deployPush($repo);
    }
    /* ----------------------------------------- */
}
