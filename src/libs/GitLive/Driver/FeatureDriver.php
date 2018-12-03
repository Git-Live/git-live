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

use Symfony\Component\Console\Output\OutputInterface;

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

        $this->GitCmdExecutor->checkout('upstream/' . $Config->develop());
        $this->GitCmdExecutor->checkout($branch, ['-b']);
    }

    /**
     * @param null|string $bransh
     * @throws Exception
     * @throws \ReflectionException
     * @return string
     */
    public function featureStatus($bransh = null)
    {
        if ($bransh === null) {
            $self_branch = $this->getSelfBranch();
            if ($self_branch === $this->Driver(ConfigDriver::class)->master()) {
                $bransh = $this->Driver(ConfigDriver::class)->develop();
            } elseif ($self_branch === $this->Driver(ConfigDriver::class)->develop()) {
                $bransh = $this->Driver(ConfigDriver::class)->master();
            } elseif ($this->Driver(HotfixDriver::class)->isHotfixOpen()) {
                $bransh = $this->Driver(ConfigDriver::class)->master();
            } elseif ($this->Driver(ReleaseDriver::class)->isReleaseOpen()) {
                $bransh = $this->Driver(ConfigDriver::class)->develop();
            } else {
                $bransh = $this->Driver(ConfigDriver::class)->develop();
            }
        }

        return $this->GitCmdExecutor->diff([$bransh, '--name-status'], false, OutputInterface::VERBOSITY_DEBUG);
    }

    /**
     * featureを変更する
     *
     * @access      public
     * @param  string $branch
     * @throws Exception
     * @throws Exception
     * @return void
     */
    public function featureChange($branch)
    {
        $Config = $this->Driver(ConfigDriver::class);
        $feature_prefix = $Config->featurePrefix();

        if (strlen($feature_prefix) > 0 && strpos($branch, $feature_prefix) !== 0) {
            $branch = $feature_prefix . $branch;
        }

        $this->GitCmdExecutor->checkout($branch);
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
}
