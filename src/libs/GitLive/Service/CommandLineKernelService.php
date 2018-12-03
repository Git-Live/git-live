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
use GitLive\Command\Feature\FeatureStatusCommand;
use GitLive\Command\Feature\ListCommand;
use GitLive\Command\Feature\PublishCommand;
use GitLive\Command\Feature\TrackCommand;
use GitLive\Command\FeatureCommand;
use GitLive\Command\GitHubPullRequest\PullRequestFeatureStart;
use GitLive\Command\GitHubPullRequest\PullRequestMerge;
use GitLive\Command\GitHubPullRequest\PullRequestPull;
use GitLive\Command\GitHubPullRequest\PullRequestTrack;
use GitLive\Command\Hotfix\HotfixCloseCommand;
use GitLive\Command\Hotfix\HotfixDestroyCommand;
use GitLive\Command\Hotfix\HotfixIsCommand;
use GitLive\Command\Hotfix\HotfixOpenCommand;
use GitLive\Command\Hotfix\HotfixPullCommand;
use GitLive\Command\Hotfix\HotfixPushCommand;
use GitLive\Command\Hotfix\HotfixStateCommand;
use GitLive\Command\Hotfix\HotfixSyncCommand;
use GitLive\Command\Hotfix\HotfixTrackCommand;
use GitLive\Command\HotfixCommand;
use GitLive\Command\InitCommand;
use GitLive\Command\LogCommand;
use GitLive\Command\MergeCommand;
use GitLive\Command\PullCommand;
use GitLive\Command\PushCommand;
use GitLive\Command\Release\ReleaseCloseCommand;
use GitLive\Command\Release\ReleaseDestroyCommand;
use GitLive\Command\Release\ReleaseIsCommand;
use GitLive\Command\Release\ReleaseOpenCommand;
use GitLive\Command\Release\ReleasePullCommand;
use GitLive\Command\Release\ReleasePushCommand;
use GitLive\Command\Release\ReleaseStateCommand;
use GitLive\Command\Release\ReleaseSyncCommand;
use GitLive\Command\Release\ReleaseTrackCommand;
use GitLive\Command\ReleaseCommand;
use GitLive\Command\ReStartCommand;
use GitLive\Command\SelfUpdateCommand;
use GitLive\Command\StartCommand;
use GitLive\Driver\Log\LogDevelopCommand;
use GitLive\Driver\Log\LogMasterCommand;
use GitLive\Driver\Merge\MergeDevelopCommand;
use GitLive\Driver\Merge\MergeMasterCommand;
use GitLive\Driver\Merge\StateDevelopCommand;
use GitLive\Driver\Merge\StateMasterCommand;

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
            'feature:status' => function () {
                return new FeatureStatusCommand();
            },

            'release:state' => function () {
                return new ReleaseStateCommand();
            },
            'release:close' => function () {
                return new ReleaseCloseCommand();
            },
            'release:destroy' => function () {
                return new ReleaseDestroyCommand();
            },
            'release:is' => function () {
                return new ReleaseIsCommand();
            },
            'release:open' => function () {
                return new ReleaseOpenCommand();
            },
            'release:pull' => function () {
                return new ReleasePullCommand();
            },
            'release:push' => function () {
                return new ReleasePushCommand();
            },
            'release:sync' => function () {
                return new ReleaseSyncCommand();
            },
            'release:track' => function () {
                return new ReleaseTrackCommand();
            },

            'hotfix:state' => function () {
                return new HotfixStateCommand();
            },
            'hotfix:close' => function () {
                return new HotfixCloseCommand();
            },
            'hotfix:destroy' => function () {
                return new HotfixDestroyCommand();
            },
            'hotfix:is' => function () {
                return new HotfixIsCommand();
            },
            'hotfix:open' => function () {
                return new HotfixOpenCommand();
            },
            'hotfix:pull' => function () {
                return new HotfixPullCommand();
            },
            'hotfix:push' => function () {
                return new HotfixPushCommand();
            },
            'hotfix:sync' => function () {
                return new HotfixSyncCommand();
            },
            'hotfix:track' => function () {
                return new HotfixTrackCommand();
            },

            'log:develop' => function () {
                return new LogDevelopCommand();
            },
            'log:master' => function () {
                return new LogMasterCommand();
            },

            'merge:develop' => function () {
                return new MergeDevelopCommand();
            },
            'merge:master' => function () {
                return new MergeMasterCommand();
            },

            'merge:state:develop' => function () {
                return new StateDevelopCommand();
            },
            'merge:state:master' => function () {
                return new StateMasterCommand();
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
