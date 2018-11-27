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

        return $this->GitCmdExecuter->branch(['--list', '"' . $feature_prefix . '*"'], true);
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

        $this->GitCmdExecuter->checkout('upstream/' . $Config->develop());
        $this->GitCmdExecuter->checkout($branch, ['-b']);
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

        $this->GitCmdExecuter->checkout($branch);
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

        $this->GitCmdExecuter->push('upstream', $branch);
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

        $this->GitCmdExecuter->push('origin', $branch);
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
            $this->GitCmdExecuter->checkout('upstream/' . $branch);
            $this->GitCmdExecuter->checkout($branch, ['-b']);
        }

        $this->GitCmdExecuter->pull('upstream', $branch);
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

        $this->GitCmdExecuter->pull('upstream', $repository);
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

        $this->GitCmdExecuter->push('upstream', ':' . $repository);
        $this->GitCmdExecuter->push('origin', ':' . $repository);
        $this->GitCmdExecuter->checkout($Config->develop());
        $this->GitCmdExecuter->branch(['-D', $repository]);
    }
}
