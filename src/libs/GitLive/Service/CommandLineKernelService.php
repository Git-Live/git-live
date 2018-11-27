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

namespace GitLive\Service;

use GitLive\Command\CleanCommand;
use GitLive\Command\Config\SetCommand;
use GitLive\Command\Feature\ChangeCommand;
use GitLive\Command\Feature\CloseCommand as FeatureCloseCommand;
use GitLive\Command\Feature\FeaturePullCommand;
use GitLive\Command\Feature\FeaturePushCommand;
use GitLive\Command\Feature\FeatureStartCommand;
use GitLive\Command\Feature\ListCommand;
use GitLive\Command\Feature\PublishCommand;
use GitLive\Command\Feature\TrackCommand;
use GitLive\Command\FeatureCommand;
use GitLive\Command\GitHubPullRequest\PullRequestFeatureStart;
use GitLive\Command\GitHubPullRequest\PullRequestMerge;
use GitLive\Command\GitHubPullRequest\PullRequestPull;
use GitLive\Command\GitHubPullRequest\PullRequestTrack;
use GitLive\Command\Hotfix\HotfixClose;
use GitLive\Command\Hotfix\HotfixDestroy;
use GitLive\Command\Hotfix\HotfixIs;
use GitLive\Command\Hotfix\HotfixOpen;
use GitLive\Command\Hotfix\HotfixPull;
use GitLive\Command\Hotfix\HotfixPush;
use GitLive\Command\Hotfix\HotfixState;
use GitLive\Command\Hotfix\HotfixSync;
use GitLive\Command\Hotfix\HotfixTrack;
use GitLive\Command\HotfixCommand;
use GitLive\Command\InitCommand;
use GitLive\Command\LogCommand;
use GitLive\Command\MergeCommand;
use GitLive\Command\PullCommand;
use GitLive\Command\PushCommand;
use GitLive\Command\Release\ReleaseClose;
use GitLive\Command\Release\ReleaseDestroy;
use GitLive\Command\Release\ReleaseIs;
use GitLive\Command\Release\ReleaseOpen;
use GitLive\Command\Release\ReleasePull;
use GitLive\Command\Release\ReleasePush;
use GitLive\Command\Release\ReleaseState;
use GitLive\Command\Release\ReleaseSync;
use GitLive\Command\Release\ReleaseTrack;
use GitLive\Command\ReleaseCommand;
use GitLive\Command\ReStartCommand;
use GitLive\Command\SelfUpdateCommand;
use GitLive\Command\StartCommand;
use GitLive\Driver\Log\LogDevelopCommand;
use GitLive\Driver\Log\LogMasterCommand;
use GitLive\Driver\Merge\MergeDevelop;
use GitLive\Driver\Merge\MergeMaster;
use GitLive\Driver\Merge\StateDevelop;
use GitLive\Driver\Merge\StateMaster;

/**
 * Class CommandLineKernelService
 *
 * @category   GitCommand
 * @package    GitLive\Service
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018/11/24
 * @codeCoverageIgnore
 */
class CommandLineKernelService
{
    public function register()
    {
        return [
            'feature:list' => function () {
                return new ListCommand;
            },
            'feature:start' => function () {
                return new FeatureStartCommand();
            },
            'feature:change' => function () {
                return new ChangeCommand();
            },
            'feature:close' => function () {
                return new FeatureCloseCommand();
            },
            'feature:publish' => function () {
                return new PublishCommand();
            },
            'feature:pull' => function () {
                return new FeaturePullCommand();
            },
            'feature:push' => function () {
                return new FeaturePushCommand();
            },
            'feature:track' => function () {
                return new TrackCommand();
            },

            'release:state' => function () {
                return new ReleaseState();
            },
            'release:close' => function () {
                return new ReleaseClose();
            },
            'release:destroy' => function () {
                return new ReleaseDestroy();
            },
            'release:is' => function () {
                return new ReleaseIs();
            },
            'release:open' => function () {
                return new ReleaseOpen();
            },
            'release:pull' => function () {
                return new ReleasePull();
            },
            'release:push' => function () {
                return new ReleasePush();
            },
            'release:sync' => function () {
                return new ReleaseSync();
            },
            'release:track' => function () {
                return new ReleaseTrack();
            },

            'hotfix:state' => function () {
                return new HotfixState();
            },
            'hotfix:close' => function () {
                return new HotfixClose();
            },
            'hotfix:destroy' => function () {
                return new HotfixDestroy();
            },
            'hotfix:is' => function () {
                return new HotfixIs();
            },
            'hotfix:open' => function () {
                return new HotfixOpen();
            },
            'hotfix:pull' => function () {
                return new HotfixPull();
            },
            'hotfix:push' => function () {
                return new HotfixPush();
            },
            'hotfix:sync' => function () {
                return new HotfixSync();
            },
            'hotfix:track' => function () {
                return new HotfixTrack();
            },

            'log:develop' => function () {
                return new LogDevelopCommand();
            },
            'log:master' => function () {
                return new LogMasterCommand();
            },

            'merge:develop' => function () {
                return new MergeDevelop();
            },
            'merge:master' => function () {
                return new MergeMaster();
            },

            'merge:state:develop' => function () {
                return new StateDevelop();
            },
            'merge:state:master' => function () {
                return new StateMaster();
            },

            'pr:feature:start' => function () {
                return new PullRequestFeatureStart();
            },
            'pr:merge' => function () {
                return new PullRequestMerge();
            },
            'pr:pull' => function () {
                return new PullRequestPull();
            },
            'pr:track' => function () {
                return new PullRequestTrack();
            },

            'config:set' => function () {
                return new SetCommand();
            },
            'feature' => function () {
                return new FeatureCommand;
            },
            'release' => function () {
                return new ReleaseCommand();
            },
            'hotfix' => function () {
                return new HotfixCommand();
            },
            'init' => function () {
                return new InitCommand();
            },
            'start' => function () {
                return new StartCommand();
            },
            're-start' => function () {
                return new ReStartCommand();
            },
            'log' => function () {
                return new LogCommand();
            },
            'merge' => function () {
                return new MergeCommand();
            },
            'push' => function () {
                return new PushCommand();
            },
            'pull' => function () {
                return new PullCommand();
            },
            'clean' => function () {
                return new CleanCommand();
            },
            'self-update' => function () {
                return new SelfUpdateCommand();
            },
        ];
    }
}
