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

namespace GitLive\Driver;

use GitLive\GitLive;

/**
 * @category               GitCommand
 * @package                Git-Live
 * @subpackage             Core
 * @author                 akito<akito-artisan@five-foxes.com>
 * @author                 suzunone<suzunone.eleven@gmail.com>
 * @copyright              Project Git Live
 * @license                MIT
 * @version                GIT: $Id$
 * @link                   https://github.com/Git-Live/git-live
 * @see                    https://github.com/Git-Live/git-live
 * @since                  Class available since Release 1.0.0
 * @backupStaticAttributes enabled
 */
class ConfigDriver extends DriverBase
{
    const FEATURE_PREFIX_NAME_KEY = 'branch.feature.prefix.name';
    const FEATURE_PREFIX_IGNORE_KEY = 'branch.feature.prefix.ignore';
    const RELEASE_PREFIX_KEY = 'branch.release.prefix.name';
    const HOTFIX_PREFIX_KEY = 'branch.hotfix.prefix.name';
    const DEPLOY_REMOTE_KEY = 'deploy.remote';
    const DEVELOP_NAME_KEY = 'branch.develop.name';
    const MASTER_NAME_KEY = 'branch.master.name';

    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * 基本デバッグ用
     */
    public static function reset()
    {
        self::$cache = [];
    }

    public function setGlobalParameter($key, $value)
    {
        if (!$this->isGitRepository()) {
            return null;
        }

        return $this->GitCmdExecuter->config(['--global', 'gitlive.' . $key, '"' . $value . '"']);
    }

    public function setLocalParameter($key, $value)
    {
        if (!$this->isGitRepository()) {
            return null;
        }

        return $this->GitCmdExecuter->config(['--local', 'gitlive.' . $key, '"' . $value . '"']);
    }

    public function setSystemParameter($key, $value)
    {
        if (!$this->isGitRepository()) {
            return null;
        }

        return $this->GitCmdExecuter->config(['--system', 'gitlive.' . $key, '"' . $value . '"']);
    }

    public function featurePrefix()
    {
        if (isset(self::$cache[__METHOD__])) {
            return self::$cache[__METHOD__];
        }

        if (strtolower($this->getParameter(self::FEATURE_PREFIX_IGNORE_KEY)) === 'true') {
            return self::$cache[__METHOD__] = '';
        }

        return self::$cache[__METHOD__] = $this->getParameter(self::FEATURE_PREFIX_NAME_KEY) ?? GitLive::DEFAULT_FEATURE_PREFIX;
    }

    public function getParameter($key)
    {
        if (!$this->isGitRepository()) {
            return null;
        }

        $res = $this->GitCmdExecuter->config(['--get', 'gitlive.' . $key]);

        if ($res === '') {
            $res = null;
        }

        return $res;
    }

    public function hotfixPrefix()
    {
        if (isset(self::$cache[__METHOD__])) {
            return self::$cache[__METHOD__];
        }

        return self::$cache[__METHOD__] = $this->getParameter(self::HOTFIX_PREFIX_KEY) ?? GitLive::DEFAULT_HOTFIX_PREFIX;
    }

    public function releasePrefix()
    {
        if (isset(self::$cache[__METHOD__])) {
            return self::$cache[__METHOD__];
        }

        return self::$cache[__METHOD__] = $this->getParameter(self::RELEASE_PREFIX_KEY) ?? GitLive::DEFAULT_RELEASE_PREFIX;
    }

    public function deployRemote()
    {
        if (isset(self::$cache[__METHOD__])) {
            return self::$cache[__METHOD__];
        }

        return self::$cache[__METHOD__] = $this->getParameter(self::DEPLOY_REMOTE_KEY) ?? GitLive::DEFAULT_DEPLOY_REMOTE_NAME;
    }

    public function develop()
    {
        if (isset(self::$cache[__METHOD__])) {
            return self::$cache[__METHOD__];
        }

        return self::$cache[__METHOD__] = $this->getParameter(self::DEVELOP_NAME_KEY) ?? GitLive::DEFAULT_DEVELOP_BRANCH_NAME;
    }

    public function master()
    {
        if (isset(self::$cache[__METHOD__])) {
            return self::$cache[__METHOD__];
        }

        return self::$cache[__METHOD__] = $this->getParameter(self::MASTER_NAME_KEY) ?? GitLive::DEFAULT_MASTER_BRANCH_NAME;
    }
}
