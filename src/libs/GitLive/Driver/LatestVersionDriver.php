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
use JapaneseDate\DateTime;

class LatestVersionDriver extends DriverBase
{
    /**
     *  新しいVersionが出ていないか確認する
     *
     * @access      public
     * @throws Exception
     * @return bool
     */
    public function ckNewVersion(): bool
    {
        $latest_version = $this->getLatestVersion();

        return (bool)version_compare(GitLive::VERSION, $latest_version, '<');
    }

    /**
     * 最終Versionを取得
     *
     * @access      public
     * @throws Exception
     * @return string
     */
    public function getLatestVersion(): string
    {
        static $latest_version;

        if ($latest_version) {
            return $latest_version;
        }

        /**
         * @var ConfigDriver $ConfigDriver
         */
        $ConfigDriver = $this->Driver(ConfigDriver::class);

        $latest_version_fetch_time = (int)$ConfigDriver->getGitLiveParameter('latestversion.fetchtime');
        $update_ck_span = (int)$ConfigDriver->getGitLiveParameter('latestversion.update_ck_span') ?: GitLive::DEFAULT_UPDATE_CK_SPAN;

        $next_fetch_date = DateTime::factory($latest_version_fetch_time + $update_ck_span);

        if (!DateTime::now()->greaterThan($next_fetch_date)) {
            return $latest_version = $ConfigDriver->getGitLiveParameter('latestversion.val');
        }

        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: PHP',
                ],
            ],
        ];

        $context = stream_context_create($opts);
        $contents = file_get_contents(GitLive::VERSION_API, false, $context);
        if (!$contents) {
            $latest_version = GitLive::VERSION;

            return $latest_version;
        }

        $arr = json_decode($contents, true);
        if (strpos($arr['tag_name'], 'v') === 0) {
            $latest_version = substr($arr['tag_name'], 1);
        } else {
            $latest_version = $arr['tag_name'];
        }

        $ConfigDriver->setLocalParameter('latestversion.fetchtime', DateTime::now()->timestamp);
        $ConfigDriver->setLocalParameter('latestversion.val', $latest_version);

        return $latest_version;
    }
}
