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
 * @category               GitCommand
 * @package                GitLive\Driver
 * @subpackage             Core
 * @author                 akito<akito-artisan@five-foxes.com>
 * @author                 suzunone<suzunone.eleven@gmail.com>
 * @copyright              Project Git Live
 * @license                MIT
 * @version                GIT: $Id$
 * @link                   https://github.com/Git-Live/git-live
 * @see                    https://github.com/Git-Live/git-live
 * @since                  2018-12-08
 * @backupStaticAttributes enabled
 */
class ConfigDriver extends DriverBase
{
    public const FEATURE_PREFIX_NAME_KEY = 'branch.feature.prefix.name';
    public const FEATURE_PREFIX_IGNORE_KEY = 'branch.feature.prefix.ignore';
    public const RELEASE_PREFIX_KEY = 'branch.release.prefix.name';
    public const HOTFIX_PREFIX_KEY = 'branch.hotfix.prefix.name';
    public const FIRE_PREFIX_NAME_KEY = 'branch.fire.prefix.name';
    public const DEPLOY_REMOTE_KEY = 'deploy.remote';
    public const DEVELOP_NAME_KEY = 'branch.develop.name';
    public const MASTER_NAME_KEY = 'branch.master.name';
    public const UPSTREAM_READ_ONLY_KEY = 'remote.upstream.readonly';
    public const DEPLOY_READ_ONLY_KEY = 'remote.deploy.readonly';

    /**
     * @var array
     */
    protected static array $cache = [];

    /**
     * Clear the local cache for testing
     * @codeCoverageIgnore
     */
    public static function reset(): void
    {
        self::$cache = [];
    }

    /**
     * Set to global config.
     *
     * @param string $key
     * @param string $value
     * @return null|string
     * @throws \ErrorException
     */
    public function setGlobalParameter(string $key, string $value): ?string
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
     * @param null|string $value
     * @return null|string
     * @throws \ErrorException
     */
    public function setLocalParameter(string $key, ?string $value): ?string
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
     * @throws \ErrorException
     */
    public function setSystemParameter(string $key, string $value): ?string
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
     * @throws \ErrorException
     */
    public function getGitLiveParameter(string $key): ?string
    {
        if (!$this->isGitRepository()) {
            return null;
        }

        $res = trim((string)$this->GitCmdExecutor->config(['--get', 'gitlive.' . $key]));

        if ($res === '') {
            $res = null;
        }

        return $res;
    }

    /**
     * Get feature prefix.
     *
     * @return null|string
     * @throws \ErrorException
     */
    public function featurePrefix(): ?string
    {
        if (isset(self::$cache[__METHOD__])) {
            return self::$cache[__METHOD__];
        }

        if (strtolower((string)$this->getGitLiveParameter(self::FEATURE_PREFIX_IGNORE_KEY)) === 'true') {
            return self::$cache[__METHOD__] = '';
        }

        return self::$cache[__METHOD__] = $this->getGitLiveParameter(self::FEATURE_PREFIX_NAME_KEY) ?? GitLive::DEFAULT_FEATURE_PREFIX;
    }

    /**
     * Get feature prefix.
     *
     * @return null|string
     * @throws \ErrorException
     */
    public function firePrefix(): ?string
    {
        return self::$cache[__METHOD__] ?? (self::$cache[__METHOD__] = $this->getGitLiveParameter(self::FIRE_PREFIX_NAME_KEY) ?? GitLive::DEFAULT_FIRE_PREFIX);
    }

    /**
     * Get hotfix prefix.
     *
     * @return null|string
     * @throws \ErrorException
     */
    public function hotfixPrefix(): ?string
    {
        return self::$cache[__METHOD__] ?? (self::$cache[__METHOD__] = $this->getGitLiveParameter(self::HOTFIX_PREFIX_KEY) ?? GitLive::DEFAULT_HOTFIX_PREFIX);
    }

    /**
     * upstream readonly flag
     *
     * @return bool
     * @throws \ErrorException
     */
    public function isUpstreamReadOnly(): bool
    {
        return self::$cache[__METHOD__] ?? (self::$cache[__METHOD__] = strtolower($this->getGitLiveParameter(self::UPSTREAM_READ_ONLY_KEY) ?? 'false') === 'true');
    }

    /**
     * release readonly flag
     *
     * @return bool
     * @throws \ErrorException
     */
    public function isDeployReadOnly(): bool
    {
        return self::$cache[__METHOD__] ?? (self::$cache[__METHOD__] = strtolower($this->getGitLiveParameter(self::DEPLOY_READ_ONLY_KEY) ?? 'false') === 'true');
    }

    /**
     * Get release prefix.
     *
     * @return null|string
     * @throws \ErrorException
     */
    public function releasePrefix(): ?string
    {
        return self::$cache[__METHOD__] ?? (self::$cache[__METHOD__] = $this->getGitLiveParameter(self::RELEASE_PREFIX_KEY) ?? GitLive::DEFAULT_RELEASE_PREFIX);
    }

    /**
     * Get deploy remote name.
     *
     * @return null|string
     * @throws \ErrorException
     */
    public function deployRemote(): ?string
    {
        return self::$cache[__METHOD__] ?? (self::$cache[__METHOD__] = $this->getGitLiveParameter(self::DEPLOY_REMOTE_KEY) ?? GitLive::DEFAULT_DEPLOY_REMOTE_NAME);
    }

    /**
     * Get development branch name.
     *
     * @return null|string
     * @throws \ErrorException
     */
    public function develop(): ?string
    {
        return self::$cache[__METHOD__] ?? (self::$cache[__METHOD__] = $this->getGitLiveParameter(self::DEVELOP_NAME_KEY) ?? GitLive::DEFAULT_DEVELOP_BRANCH_NAME);
    }

    /**
     * Get master branch name.
     *
     * @return null|string
     * @throws \ErrorException
     */
    public function master(): ?string
    {
        return self::$cache[__METHOD__] ?? (self::$cache[__METHOD__] = $this->getGitLiveParameter(self::MASTER_NAME_KEY) ?? GitLive::DEFAULT_MASTER_BRANCH_NAME);
    }

    /**
     * @return void
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     */
    public function interactiveConfigurations(): void
    {
        $this->interactiveConfiguration(self::MASTER_NAME_KEY);
        $this->interactiveConfiguration(self::DEVELOP_NAME_KEY);

        if ($this->interactiveConfiguration(self::FEATURE_PREFIX_IGNORE_KEY) !== 'true') {
            $this->interactiveConfiguration(self::FEATURE_PREFIX_NAME_KEY);
        }

        $this->interactiveConfiguration(self::RELEASE_PREFIX_KEY);
        $this->interactiveConfiguration(self::HOTFIX_PREFIX_KEY);
        $this->interactiveConfiguration(self::FIRE_PREFIX_NAME_KEY);
        $this->interactiveConfiguration(self::DEPLOY_REMOTE_KEY);
        $this->interactiveConfiguration(self::UPSTREAM_READ_ONLY_KEY);
        $this->interactiveConfiguration(self::DEPLOY_READ_ONLY_KEY);
    }

    /**
     * @param string $config_key
     * @return string
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     */
    public function interactiveConfiguration(string $config_key): string
    {
        switch ($config_key) {
            case self::FEATURE_PREFIX_NAME_KEY:
                $default = GitLive::DEFAULT_FEATURE_PREFIX;
                $message = __('Specify feature branch prefix.') . __('default:' . $default);

                break;
            case self::FEATURE_PREFIX_IGNORE_KEY:
                $default = 'false';
                $message = __('If `true` is specified, feature branch prefix will be disabled.') . __('default:' . $default);

                break;
            case self::RELEASE_PREFIX_KEY:
                $default = GitLive::DEFAULT_RELEASE_PREFIX;
                $message = __('Specify release branch prefix.') . __('default:' . $default);

                break;
            case self::HOTFIX_PREFIX_KEY:
                $default = GitLive::DEFAULT_HOTFIX_PREFIX;
                $message = __('Specify hotfix branch prefix.') . __('default:' . $default);

                break;
            case self::FIRE_PREFIX_NAME_KEY:
                $default = GitLive::DEFAULT_FIRE_PREFIX;
                $message = __('Specify fire branch prefix.') . __('default:' . $default);

                break;
            case self::DEPLOY_REMOTE_KEY:
                $default = GitLive::DEFAULT_DEPLOY_REMOTE_NAME;
                $message = __('Specify the remote name for deployment.') . __('default:' . $default);

                break;
            case self::DEVELOP_NAME_KEY:
                $default = GitLive::DEFAULT_DEVELOP_BRANCH_NAME;
                $message = __('Specify the branch name for development.') . __('default:' . $default);

                break;
            case self::MASTER_NAME_KEY:
                $default = trim((string)$this->GitCmdExecutor->config(['--get', 'init.defaultBranch'])) ?: GitLive::DEFAULT_MASTER_BRANCH_NAME;
                $message = __('Specify the branch name for main(master).') . __('default:' . $default);

                break;
            case self::UPSTREAM_READ_ONLY_KEY:
                $default = 'false';
                $message = __('If `true` is specified, set upstream to read only.') . __('default:' . $default);

                break;
            case self::DEPLOY_READ_ONLY_KEY:
                $default = 'false';
                $message = __('If `true` is specified, set deploy to read only.') . __('default:' . $default);

                break;
            default:
                throw new Exception(__('fatal: unknown config key : ' . $config_key));
        }

        $value = $this->interactiveShell($message, $default);

        if ($default === 'false') {
            $value = strtolower((string)$value);
            if ($value !== 'true') {
                $value = $default;
            }
        }

        $this->setLocalParameter($config_key, $value);

        return (string)$value;
    }
}
