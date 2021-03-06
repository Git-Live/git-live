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
 * Class ConfigDriver
 *
 * Operations like git config command
 *
 * @category   GitCommand
 * @package    GitLive\Driver
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-08
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
     * Clear the local cache for testing
     * @codeCoverageIgnore
     */
    public static function reset()
    {
        self::$cache = [];
    }

    /**
     * Set to global config.
     *
     * @param string $key
     * @param string $value
     * @return null|string
     */
    public function setGlobalParameter($key, $value)
    {
        if (!$this->isGitRepository()) {
            return null;
        }

        return $this->GitCmdExecutor->config(['--global', 'gitlive.' . $key, '"' . $value . '"']);
    }

    /**
     * Set to local config.
     *
     * @param string $key
     * @param string $value
     * @return null|string
     */
    public function setLocalParameter($key, $value)
    {
        if (!$this->isGitRepository()) {
            return null;
        }

        return $this->GitCmdExecutor->config(['--local', 'gitlive.' . $key, '"' . $value . '"']);
    }

    /**
     * Set to system config.
     *
     * @param string $key
     * @param string $value
     * @return null|string
     */
    public function setSystemParameter($key, $value)
    {
        if (!$this->isGitRepository()) {
            return null;
        }

        return $this->GitCmdExecutor->config(['--system', 'gitlive.' . $key, '"' . $value . '"']);
    }

    /**
     * Get the setting of git-live.
     *
     * @param string $key
     * @return null|string
     */
    public function getGitLiveParameter($key)
    {
        if (!$this->isGitRepository()) {
            return null;
        }

        $res = trim($this->GitCmdExecutor->config(['--get', 'gitlive.' . $key]));

        if ($res === '') {
            $res = null;
        }

        return $res;
    }

    /**
     * Get feature prefix.
     *
     * @return null|string
     */
    public function featurePrefix()
    {
        if (isset(self::$cache[__METHOD__])) {
            return self::$cache[__METHOD__];
        }

        if (strtolower($this->getGitLiveParameter(self::FEATURE_PREFIX_IGNORE_KEY)) === 'true') {
            return self::$cache[__METHOD__] = '';
        }

        return self::$cache[__METHOD__] = $this->getGitLiveParameter(self::FEATURE_PREFIX_NAME_KEY) ?? GitLive::DEFAULT_FEATURE_PREFIX;
    }

    /**
     * Get hotfix prefix.
     *
     * @return null|string
     */
    public function hotfixPrefix()
    {
        if (isset(self::$cache[__METHOD__])) {
            return self::$cache[__METHOD__];
        }

        return self::$cache[__METHOD__] = $this->getGitLiveParameter(self::HOTFIX_PREFIX_KEY) ?? GitLive::DEFAULT_HOTFIX_PREFIX;
    }

    /**
     * Get release prefix.
     *
     * @return null|string
     */
    public function releasePrefix()
    {
        if (isset(self::$cache[__METHOD__])) {
            return self::$cache[__METHOD__];
        }

        return self::$cache[__METHOD__] = $this->getGitLiveParameter(self::RELEASE_PREFIX_KEY) ?? GitLive::DEFAULT_RELEASE_PREFIX;
    }

    /**
     * Get deploy remote name.
     *
     * @return null|string
     */
    public function deployRemote()
    {
        if (isset(self::$cache[__METHOD__])) {
            return self::$cache[__METHOD__];
        }

        return self::$cache[__METHOD__] = $this->getGitLiveParameter(self::DEPLOY_REMOTE_KEY) ?? GitLive::DEFAULT_DEPLOY_REMOTE_NAME;
    }

    /**
     * Get development branch name.
     *
     * @return null|string
     */
    public function develop()
    {
        if (isset(self::$cache[__METHOD__])) {
            return self::$cache[__METHOD__];
        }

        return self::$cache[__METHOD__] = $this->getGitLiveParameter(self::DEVELOP_NAME_KEY) ?? GitLive::DEFAULT_DEVELOP_BRANCH_NAME;
    }

    /**
     * Get master branch name.
     *
     * @return null|string
     */
    public function master()
    {
        if (isset(self::$cache[__METHOD__])) {
            return self::$cache[__METHOD__];
        }

        return self::$cache[__METHOD__] = $this->getGitLiveParameter(self::MASTER_NAME_KEY) ?? GitLive::DEFAULT_MASTER_BRANCH_NAME;
    }
}
