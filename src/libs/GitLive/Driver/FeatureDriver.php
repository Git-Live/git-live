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

use GitLive\Support\Collection;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FeatureDriver
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
 */
class FeatureDriver extends DriverBase
{
    /**
     * featureの一覧を取得する
     *
     * @access      public
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return string
     */
    public function featureList(): string
    {
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = $Config->featurePrefix();

        return (string)$this->GitCmdExecutor->branch(['--list', '"' . $feature_prefix . '*"'], true);
    }

    /**
     * featureの一覧を取得する
     *
     * @access      public
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return string
     */
    public function mergedFeatureList(): string
    {
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = $Config->featurePrefix();

        return (string)$this->GitCmdExecutor->branch(['--list', '"' . $feature_prefix . '*"', '--merged', ], true);
    }

    /**
     * featureの一覧を取得する
     *
     * @access      public
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return string
     */
    public function noMergedFeatureList(): string
    {
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = $Config->featurePrefix();

        return (string)$this->GitCmdExecutor->branch(['--list', '"' . $feature_prefix . '*"', '--no-merged', ], true);
    }

    /**
     * featureを開始する
     *
     * @access      public
     * @param string $branch
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @throws \GitLive\Exception
     * @return void
     */
    public function featureStart(string $branch): void
    {
        $Fetch = $this->Driver(FetchDriver::class);
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = (string)$Config->featurePrefix();

        $Fetch->all();
        $Fetch->upstream();
        if ($feature_prefix !== '' && strpos($branch, $feature_prefix) !== 0) {
            $branch = $feature_prefix . $branch;
        }

        if ($this->Driver(BranchDriver::class)->isBranchExistsAll($branch)) {
            throw new \GitLive\Exception(sprintf(__('%s branch is duplicate.'), $branch));
        }

        $this->GitCmdExecutor->checkout('upstream/' . $Config->develop());
        $this->GitCmdExecutor->checkout($branch, ['-b']);
    }

    /**
     * @param string|null $branch
     * @return null|string
     *@throws \GitLive\Driver\Exception
     * @throws \ErrorException
     */
    public function featureStatus(?string $branch = null): ?string
    {
        if ($branch === null) {
            $self_branch = $this->getSelfBranch();
            if ($self_branch === $this->Driver(ConfigDriver::class)->master()) {
                $branch = $this->Driver(ConfigDriver::class)->develop();
            } elseif ($self_branch === $this->Driver(ConfigDriver::class)->develop()) {
                $branch = $this->Driver(ConfigDriver::class)->master();
            } /** @noinspection NotOptimalIfConditionsInspection */ elseif (strpos($this->Driver(ConfigDriver::class)->hotfixPrefix(), $self_branch) === 0 && $this->Driver(HotfixDriver::class)->isHotfixOpen()) {
                $branch = $this->Driver(ConfigDriver::class)->master();
            } /** @noinspection NotOptimalIfConditionsInspection */ elseif (strpos($this->Driver(ConfigDriver::class)->releasePrefix(), $self_branch) === 0 && $this->Driver(ReleaseDriver::class)->isReleaseOpen()) {
                $branch = $this->Driver(ConfigDriver::class)->develop();
            } else {
                $branch = $this->Driver(ConfigDriver::class)->develop();
            }
        } elseif (strpos($branch, $this->Driver(ConfigDriver::class)->hotfixPrefix()) !== 0
            && strpos($branch, $this->Driver(ConfigDriver::class)->releasePrefix()) !== 0
            && ((string)$this->Driver(ConfigDriver::class)->featurePrefix() !== ''
                && strpos($branch, $this->Driver(ConfigDriver::class)->featurePrefix()) !== 0)
        ) {
            $branch = $this->Driver(ConfigDriver::class)->featurePrefix() . $branch;
        }

        return $this->GitCmdExecutor->diff([$branch, '--name-status'], false, OutputInterface::VERBOSITY_DEBUG);
    }

    /**
     * featureを変更する
     *
     * @access      public
     * @param string $branch
     * @param array|Collection $option
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return bool|string
     */
    public function featureChange(string $branch, $option = [])
    {
        $Config = $this->Driver(ConfigDriver::class);
        $feature_prefix = (string)$Config->featurePrefix();

        $feature_branch = $branch;
        if ($feature_prefix !== '' && strpos($branch, $feature_prefix) !== 0) {
            $feature_branch = $feature_prefix . $branch;
        }

        $Fetch = $this->Driver(FetchDriver::class);

        $Fetch->all();

        $branch_list = $this->Driver(BranchDriver::class)->branchListAll();

        if ($branch_list->search($feature_branch) !== false) {
            return $this->GitCmdExecutor->checkout($feature_branch, $option, false, OutputInterface::VERBOSITY_VERY_VERBOSE);
        }

        if ($branch === $this->Driver(ConfigDriver::class)->master() ||
            $branch === $this->Driver(ConfigDriver::class)->develop()) {
            $res = $this->changeRemoteIf($branch_list, $branch);
            if ($res !== false) {
                return $res;
            }
        }

        $res = $this->changeRemoteIf($branch_list, $feature_branch, $option);
        if ($res !== false) {
            return $res;
        }

        return sprintf('Error:' . __('Feature name %s not found'), $feature_branch);
    }

    /**
     * 共用Repositoryにfeatureを送信する
     *
     * @access      public
     * @param string|null $branch OPTIONAL:NULL
     * @return string
     *@throws \Exception
     */
    public function featurePublish(?string $branch = null): string
    {
        $Fetch = $this->Driver(FetchDriver::class);
        $Config = $this->Driver(ConfigDriver::class);

        if ($Config->isUpstreamReadOnly()) {
            return 'Error:' . __('upstream remote repository is readonly.');
        }

        $feature_prefix = (string)$Config->featurePrefix();

        $Fetch->all();
        $Fetch->upstream();

        if ($branch === null) {
            $branch = $this->getSelfBranchRef();
        } elseif ($feature_prefix !== '' && strpos($branch, $feature_prefix) !== 0) {
            $branch = $feature_prefix . $branch;
        }

        return (string)$this->GitCmdExecutor->push('upstream', $branch);
    }

    /**
     * 自分のリモートRepositoryにfeatureを送信する
     *
     * @access      public
     * @param string|null $branch OPTIONAL:NULL
     * @return string
     *@throws \Exception
     */
    public function featurePush(?string $branch = null): string
    {
        $Fetch = $this->Driver(FetchDriver::class);
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = (string)$Config->featurePrefix();

        $Fetch->all();
        $Fetch->upstream();

        if ($branch === null) {
            $branch = $this->getSelfBranchRef();
        } elseif ($feature_prefix !== '' && strpos($branch, $feature_prefix) !== 0) {
            $branch = $feature_prefix . $branch;
        }

        return (string)$this->GitCmdExecutor->push('origin', $branch);
    }

    /**
     * 共用Repositoryから他人のfeatureを取得する
     *
     * @access      public
     * @param string $branch
     * @throws \Exception
     * @return string
     */
    public function featureTrack(string $branch): string
    {
        $Fetch = $this->Driver(FetchDriver::class);
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = (string)$Config->featurePrefix();

        $Fetch->all();
        $Fetch->upstream();

        if ($feature_prefix !== '' && strpos($branch, $feature_prefix) !== 0) {
            $branch = $feature_prefix . $branch;
        }

        $self_repository = $this->getSelfBranch();
        $branch_list = $this->Driver(BranchDriver::class)->branchListAll();
        $remote_branch = 'remotes/upstream/' . $branch;
        if ($branch_list->search($remote_branch) === false) {
            throw new Exception(printf(__('%s could not read from remote repository.'), $remote_branch));
        }
        if ($branch_list->search($branch) === false) {
            $this->GitCmdExecutor->checkout('upstream/' . $branch);
            $this->GitCmdExecutor->checkout($branch, ['-b']);
        } elseif ($self_repository !== $branch) {
            $this->GitCmdExecutor->checkout($branch);
        }

        return (string)$this->GitCmdExecutor->pull('upstream', $branch);
    }

    /**
     * 共用Repositoryからpullする
     *
     * @access      public
     * @param string|null $branch OPTIONAL:NULL
     * @return string
     *@throws \Exception
     */
    public function featurePull(?string $branch = null): string
    {
        $Fetch = $this->Driver(FetchDriver::class);
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = $Config->featurePrefix();

        $Fetch->all();
        $Fetch->upstream();

        if ($branch === null) {
            $branch = $this->getSelfBranch();
        } elseif (strpos($branch, $feature_prefix) !== 0) {
            $branch = $feature_prefix . $branch;
        }

        $branch_list = $this->Driver(BranchDriver::class)->branchListAll();
        $res = '';

        $remote_branch = 'remotes/upstream/' . $branch;
        if ($branch_list->search($remote_branch) !== false) {
            $res .= $this->GitCmdExecutor->pull('upstream', $branch);
        }

        $remote_branch = 'remotes/origin/' . $branch;
        if ($branch_list->search($remote_branch) !== false) {
            $res .= $this->GitCmdExecutor->pull('origin', $branch);
        }

        return $res;
    }

    /**
     * featureを閉じる
     *
     * @access      public
     * @param string|null $repository OPTIONAL:NULL
     * @return string
     *@throws \Exception
     */
    public function featureClose(?string $repository = null): string
    {
        $Fetch = $this->Driver(FetchDriver::class);
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = (string)$Config->featurePrefix();

        $Fetch->all();
        $Fetch->upstream();
        $feature_branch = $this->getSelfBranch();
        switch ($feature_branch) {
            case 'refs/heads/' . $Config->develop():
            case 'refs/heads/' . $Config->master():
            case $Config->develop():
            case $Config->master():
            return sprintf('Error:' . __('%s branch is readonly.'), $this->getSelfBranch());
        }
        if ($repository === null) {
            $repository = $this->getSelfBranch();
        } elseif ($feature_prefix !== '' && strpos($repository, $feature_prefix) !== 0) {
            $repository = $feature_prefix . $repository;
        }
        if (!$Config->isUpstreamReadOnly()) {
            $this->GitCmdExecutor->push('upstream', ':' . $repository);
        }

        $this->GitCmdExecutor->push('origin', ':' . $repository);
        $this->GitCmdExecutor->checkout($Config->develop());
        $this->GitCmdExecutor->branch(['-D', $repository]);

        return $feature_branch . ' was removed.';
    }

    /**
     * @param Collection $branch_list
     * @param string $feature_branch
     * @param array|Collection $option
     * @return bool|string
     */
    protected function changeRemoteIf(Collection $branch_list, string $feature_branch, $option = [])
    {
        $remote_branch = 'remotes/origin/' . $feature_branch;
        $res = $this->changeIf($branch_list, $remote_branch, $feature_branch, $option);
        if ($res !== false) {
            return $res;
        }

        $remote_branch = 'remotes/upstream/' . $feature_branch;
        $res = $this->changeIf($branch_list, $remote_branch, $feature_branch, $option);
        if ($res !== false) {
            return $res;
        }

        return false;
    }

    /**
     * @param Collection $branch_list
     * @param string $remote_branch
     * @param string $feature_branch
     * @param array|Collection $option
     * @return bool|string
     */
    protected function changeIf(Collection $branch_list, string $remote_branch, string $feature_branch, $option = [])
    {
        $option = collect($option);
        if ($branch_list->search($remote_branch) !== false) {
            $this->GitCmdExecutor->checkout($remote_branch, $option, false, OutputInterface::VERBOSITY_VERY_VERBOSE);

            $option[] = '-b';

            return $this->GitCmdExecutor->checkout($feature_branch, $option, false, OutputInterface::VERBOSITY_VERY_VERBOSE);
        }

        return false;
    }
}
