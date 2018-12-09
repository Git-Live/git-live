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
     * @throws Exception
     * @throws Exception
     * @return string
     */
    public function featureList()
    {
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = $Config->featurePrefix();

        return $this->GitCmdExecutor->branch(['--list', '"' . $feature_prefix . '*"'], true);
    }

    /**
     * featureを開始する
     *
     * @access      public
     * @param  string $branch
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws \GitLive\Exception
     * @return void
     */
    public function featureStart($branch)
    {
        $Fetch = $this->Driver(FetchDriver::class);
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = $Config->featurePrefix();

        $Fetch->all();
        $Fetch->upstream();
        if (strlen($feature_prefix) > 0 && strpos($branch, $feature_prefix) !== 0) {
            $branch = $feature_prefix . $branch;
        }

        if ($this->Driver(BranchDriver::class)->hasBranch($branch)) {
            throw new \GitLive\Exception(sprintf(__('%s branch is duplicate.'), $branch));
        }

        $this->GitCmdExecutor->checkout('upstream/' . $Config->develop());
        $this->GitCmdExecutor->checkout($branch, ['-b']);
    }

    /**
     * @param null|string $branch
     * @throws Exception
     * @throws \ReflectionException
     * @return string
     */
    public function featureStatus($branch = null)
    {
        if ($branch === null) {
            $self_branch = $this->getSelfBranch();
            if ($self_branch === $this->Driver(ConfigDriver::class)->master()) {
                $branch = $this->Driver(ConfigDriver::class)->develop();
            } elseif ($self_branch === $this->Driver(ConfigDriver::class)->develop()) {
                $branch = $this->Driver(ConfigDriver::class)->master();
            } elseif ($this->Driver(HotfixDriver::class)->isHotfixOpen()) {
                $branch = $this->Driver(ConfigDriver::class)->master();
            } elseif ($this->Driver(ReleaseDriver::class)->isReleaseOpen()) {
                $branch = $this->Driver(ConfigDriver::class)->develop();
            } else {
                $branch = $this->Driver(ConfigDriver::class)->develop();
            }
        }

        return $this->GitCmdExecutor->diff([$branch, '--name-status'], false, OutputInterface::VERBOSITY_DEBUG);
    }

    /**
     * featureを変更する
     *
     * @access      public
     * @param  string $branch
     * @param array   $option
     * @throws Exception
     * @return string
     */
    public function featureChange($branch, $option = [])
    {
        $Config = $this->Driver(ConfigDriver::class);
        $feature_prefix = $Config->featurePrefix();

        $feature_branch = $branch;
        if (strlen($feature_prefix) > 0 && strpos($branch, $feature_prefix) !== 0) {
            $feature_branch = $feature_prefix . $branch;
        }

        $Fetch = $this->Driver(FetchDriver::class);

        $Fetch->all();

        $branch_list = $this->Driver(BranchDriver::class)->branchListAll();

        if ($branch_list->search($feature_branch)) {
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

        return sprintf(__('error: feature name %s not found'), $feature_branch);
    }

    /**
     * 共用Repositoryにfeatureを送信する
     *
     * @access      public
     * @param  null|string $branch OPTIONAL:NULL
     * @throws \Exception
     * @return void
     */
    public function featurePublish($branch = null)
    {
        $Fetch = $this->Driver(FetchDriver::class);
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = $Config->featurePrefix();

        $Fetch->all();
        $Fetch->upstream();

        if ($branch === null) {
            $branch = $this->getSelfBranchRef();
        } elseif (strlen($feature_prefix) > 0 && strpos($branch, $feature_prefix) !== 0) {
            $branch = $feature_prefix . $branch;
        }

        $this->GitCmdExecutor->push('upstream', $branch);
    }

    /**
     * 自分のリモートRepositoryにfeatureを送信する
     *
     * @access      public
     * @param  null|string $branch OPTIONAL:NULL
     * @throws \Exception
     * @return void
     */
    public function featurePush($branch = null)
    {
        $Fetch = $this->Driver(FetchDriver::class);
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = $Config->featurePrefix();

        $Fetch->all();
        $Fetch->upstream();

        if ($branch === null) {
            $branch = $this->getSelfBranchRef();
        } elseif (strlen($feature_prefix) > 0 && strpos($branch, $feature_prefix) !== 0) {
            $branch = $feature_prefix . $branch;
        }

        $this->GitCmdExecutor->push('origin', $branch);
    }

    /**
     * 共用Repositoryから他人のfeatureを取得する
     *
     * @access      public
     * @param  string $branch
     * @throws \Exception
     * @return void
     */
    public function featureTrack($branch)
    {
        $Fetch = $this->Driver(FetchDriver::class);
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = $Config->featurePrefix();

        $Fetch->all();
        $Fetch->upstream();

        $self_repository = $this->getSelfBranch();
        if (strlen($feature_prefix) > 0 && strpos($branch, $feature_prefix) !== 0) {
            $branch = $feature_prefix . $branch;
        }
        if ($self_repository !== $branch) {
            $this->GitCmdExecutor->checkout('upstream/' . $branch);
            $this->GitCmdExecutor->checkout($branch, ['-b']);
        }

        $this->GitCmdExecutor->pull('upstream', $branch);
    }

    /**
     * 共用Repositoryからpullする
     *
     * @access      public
     * @param  null|string $repository OPTIONAL:NULL
     * @throws \Exception
     * @return void
     */
    public function featurePull($repository = null)
    {
        $Fetch = $this->Driver(FetchDriver::class);
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = $Config->featurePrefix();

        $Fetch->all();
        $Fetch->upstream();

        if ($repository === null) {
            $repository = $this->getSelfBranchRef();
        } elseif (strpos($repository, $feature_prefix) !== 0) {
            $repository = $feature_prefix . $repository;
        }

        $this->GitCmdExecutor->pull('upstream', $repository);
    }

    /**
     * featureを閉じる
     *
     * @access      public
     * @param  null|string $repository OPTIONAL:NULL
     * @throws \Exception
     * @return void
     */
    public function featureClose($repository = null)
    {
        $Fetch = $this->Driver(FetchDriver::class);
        $Config = $this->Driver(ConfigDriver::class);

        $feature_prefix = $Config->featurePrefix();

        $Fetch->all();
        $Fetch->upstream();

        if ($repository === null) {
            $repository = $this->getSelfBranch();
        } elseif (strlen($feature_prefix) > 0 && strpos($repository, $feature_prefix) !== 0) {
            $repository = $feature_prefix . $repository;
        }

        $this->GitCmdExecutor->push('upstream', ':' . $repository);
        $this->GitCmdExecutor->push('origin', ':' . $repository);
        $this->GitCmdExecutor->checkout($Config->develop());
        $this->GitCmdExecutor->branch(['-D', $repository]);
    }

    /**
     * @param Collection $branch_list
     * @param string     $feature_branch
     * @param array      $option
     * @return bool|string
     */
    protected function changeRemoteIf($branch_list, $feature_branch, $option = [])
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
     * @param string     $remote_branch
     * @param string     $feature_branch
     * @param array      $option
     * @return bool|string
     */
    protected function changeIf($branch_list, $remote_branch, $feature_branch, $option = [])
    {
        if ($branch_list->search($remote_branch)) {
            $this->GitCmdExecutor->checkout($remote_branch, [], false, OutputInterface::VERBOSITY_VERY_VERBOSE);

            $option = ['-b'];

            return $this->GitCmdExecutor->checkout($feature_branch, $option, false, OutputInterface::VERBOSITY_VERY_VERBOSE);
        }

        return false;
    }
}
