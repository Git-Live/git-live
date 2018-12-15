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
use GitLive\Command\Feature\FeatureCloseCommand as FeatureCloseCommand;
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
use GitLive\Command\Log\LogDevelopCommand;
use GitLive\Command\Log\LogMasterCommand;
use GitLive\Command\LogCommand;
use GitLive\Command\Merge\MergeDevelopCommand;
use GitLive\Command\Merge\MergeMasterCommand;
use GitLive\Command\Merge\StateDevelopCommand;
use GitLive\Command\Merge\StateMasterCommand;
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
 */
class CommandLineKernelService
{
    public function app()
    {
        $app = [
            CleanCommand::class,
            SetCommand::class,
            ChangeCommand::class,
            FeatureCloseCommand::class,
            FeaturePullCommand::class,
            FeaturePushCommand::class,
            FeatureStartCommand::class,
            FeatureStatusCommand::class,
            ListCommand::class,
            PublishCommand::class,
            TrackCommand::class,
            FeatureCommand::class,
            PullRequestFeatureStart::class,
            PullRequestMerge::class,
            PullRequestPull::class,
            PullRequestTrack::class,
            HotfixCloseCommand::class,
            HotfixDestroyCommand::class,
            HotfixIsCommand::class,
            HotfixOpenCommand::class,
            HotfixPullCommand::class,
            HotfixPushCommand::class,
            HotfixStateCommand::class,
            HotfixSyncCommand::class,
            HotfixTrackCommand::class,
            HotfixCommand::class,
            InitCommand::class,
            LogCommand::class,
            MergeCommand::class,
            PullCommand::class,
            PushCommand::class,
            ReleaseCloseCommand::class,
            ReleaseDestroyCommand::class,
            ReleaseIsCommand::class,
            ReleaseOpenCommand::class,
            ReleasePullCommand::class,
            ReleasePushCommand::class,
            ReleaseStateCommand::class,
            ReleaseSyncCommand::class,
            ReleaseTrackCommand::class,
            ReleaseCommand::class,
            ReStartCommand::class,
            SelfUpdateCommand::class,
            StartCommand::class,
            LogDevelopCommand::class,
            LogMasterCommand::class,
            MergeDevelopCommand::class,
            MergeMasterCommand::class,
            StateDevelopCommand::class,
            StateMasterCommand::class,
        ];

        return collect($app)
            ->mapWithKeys(function ($item) {
                return [$item::getSignature() => function () use ($item) {
                    return new $item;
                }];
            })
            ->toArray();
    }
}
