<?php
/**
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
 * @since      Class available since Release 1.0.0
 */

namespace GitLive;


use GitLive\Application\Application;
use GitLive\Application\Container;
use GitLive\Support\FileSystem;
use GitLive\Support\FileSystemInterface;
use GitLive\Support\InteractiveShell;
use GitLive\Support\InteractiveShellInterface;
use GitLive\Support\SystemCommand;
use GitLive\Support\SystemCommandInterface;

/**
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
 * @since      Class available since Release 1.0.0
 */
class GitLive extends GitBase
{
    /**
     * @var string バージョン情報取得API
     */
    const VERSION_API = 'https://api.github.com/repos/Git-Live/git-live/releases/latest';

    /**
     * @var int デフォルトのアップデートチェック期間
     */
    const DEFAULT_UPDATE_CK_SPAN = 86000;

    /**
     * @var string バージョン
     */
    const VERSION = '1.0.0';

    /**
     * @var string バージョンコード
     */
    const VERSION_CODENAME = 'Anpan';

    const DEFAULT_DEPLOY_REMOTE_NAME = 'deploy';

    /**
     * @var string デフォルトの開発ブランチ
     */
    const DEFAULT_DEVELOP_BRANCH_NAME = 'develop';

    /**
     * @var string デフォルトのマスターブランチ
     */
    const DEFAULT_MASTER_BRANCH_NAME = 'master';


    /**
     * @var string デフォルトのfeatureプレフィクス
     */
    const DEFAULT_FEATURE_PREFIX = 'feature/';

    /**
     * @var string デフォルトのreleaseプレフィクス
     */
    const DEFAULT_RELEASE_PREFIX = 'release/';


    /**
     * @var string デフォルトのhotfixプレフィクス
     */
    const DEFAULT_HOTFIX_PREFIX = 'hotfix/';

    /**
     * 更新チェックの間隔
     *
     * @access      protected
     * @var         int
     */
    protected $update_ck_span = 1200;


    /**
     * GitLive constructor.
     */
    public function __construct()
    {
        Container::bind(
            SystemCommandInterface::class,
            SystemCommand::class
        );

        Container::bind(
            InteractiveShellInterface::class,
            InteractiveShell::class
        );

        Container::bind(
            FileSystemInterface::class,
            FileSystem::class
        );


        Container::bindContext('$GitLive', $this);
    }


    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function execute()
    {

        $application = new Application();
        $application->run();
    }
    /**
     *  新しいVersionが出ていないか確認する
     *
     * @access      public
     * @return bool
     * @throws \ReflectionException
     */
    public function ckNewVersion()
    {
        $latest_version = $this->getLatestVersion();

        return (bool)version_compare(GitLive::VERSION, $latest_version, '<');
    }

    /**
     * 最終Versionを取得
     *
     * @access      public
     * @return string
     * @throws \ReflectionException
     */
    public function getLatestVersion()
    {
        static $latest_version;

        if ($latest_version) {
            return $latest_version;
        }

        /**
         * @var ConfigDriver $ConfigDriver
         */
        $ConfigDriver = $this->Driver('Config');
        $latest_version_fetch_time = (int)$ConfigDriver->getParameter('latestversion.fetchtime');


        $update_ck_span = (int)$ConfigDriver->getParameter('latestversion.update_ck_span') ?: GitLive::DEFAULT_UPDATE_CK_SPAN;


        if (!empty($latest_version_fetch_time) && (time() - $latest_version_fetch_time) < $update_ck_span) {
            return $latest_version = $ConfigDriver->getParameter('latestversion.val');
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
        if (substr($arr['tag_name'], 0, 1) === 'v') {
            $latest_version = substr($arr['tag_name'], 1);
        } else {
            $latest_version = $arr['tag_name'];
        }

        $ConfigDriver->setLocalParameter('latestversion.fetchtime', time());
        $ConfigDriver->setLocalParameter('latestversion.val', $latest_version);

        return $latest_version;
    }

}

