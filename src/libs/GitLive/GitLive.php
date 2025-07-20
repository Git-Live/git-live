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
 * Class GitLive
 *
 * @category   GitCommand
 * @package    GitLive
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-16
 * @codeCoverageIgnore
 */
class GitLive extends GitBase
{
    /**
     * @var string バージョン情報取得API
     */
    public const VERSION_API = 'https://api.github.com/repos/Git-Live/git-live/releases/latest';

    /**
     * @var int デフォルトのアップデートチェック期間
     */
    public const DEFAULT_UPDATE_CK_SPAN = 86000;

    /**
     * @var string バージョン
     */
    public const VERSION = '4.0.4-RC01';

    /**
     * @var string バージョンコード
     */
    public const VERSION_CODENAME = 'ChocolateCoronet';

    /**
     *
     */
    public const DEFAULT_DEPLOY_REMOTE_NAME = 'deploy';

    /**
     * @var string デフォルトの開発ブランチ
     */
    public const DEFAULT_DEVELOP_BRANCH_NAME = 'develop';

    /**
     * @var string デフォルトのマスターブランチ
     */
    public const DEFAULT_MASTER_BRANCH_NAME = 'master';

    /**
     * @var string デフォルトのfeatureプレフィクス
     */
    public const DEFAULT_FEATURE_PREFIX = 'feature/';

    /**
     * @var string デフォルトのreleaseプレフィクス
     */
    public const DEFAULT_RELEASE_PREFIX = 'release/';

    /**
     * @var string デフォルトのhotfixプレフィクス
     */
    public const DEFAULT_HOTFIX_PREFIX = 'hotfix/';

    /**
     * @var string デフォルトのfireプレフィクス
     */
    public const DEFAULT_FIRE_PREFIX = 'fire/';

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
     * @throws \Exception
     */
    public function execute(): void
    {
        $application = new Application();
        $application->run();
    }
}
