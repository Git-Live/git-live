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
class PullRequestDriver extends DriverBase
{
    /**
     *  pr feature start
     *
     * @access      public
     * @param  string $pull_request_number
     * @param  string $branch
     * @throws Exception
     * @throws \GitLive\Exception
     * @return void
     */
    public function featureStart($pull_request_number, $branch)
    {
        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();
        $this->GitCmdExecuter->fetchPullRequest();

        if (strpos($branch, $this->Driver(ConfigDriver::class)->featurePrefix()) !== 0) {
            $branch = $this->Driver(ConfigDriver::class)->featurePrefix() . $branch;
        }

        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout($branch, ['-b']);
        $self_repository = $this->getSelfBranchRef();

        if (!'refs/heads/' . $branch === $self_repository) {
            throw new \GitLive\Exception(__('Feature branch create fail.'));
        }

        $upstream_repository = 'pull/' . $pull_request_number . '/head';
        $this->GitCmdExecuter->pull('upstream', $upstream_repository);
    }

    /**
     *  pr feature start-soft
     *
     * @access      public
     * @param  string $pull_request_number
     * @param  string $branch
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @return void
     */
    public function featureStartSoft($pull_request_number, $branch)
    {
        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();
        $this->GitCmdExecuter->fetchPullRequest();

        if (strpos($branch, $this->Driver(ConfigDriver::class)->featurePrefix()) !== 0) {
            $branch = $this->Driver(ConfigDriver::class)->featurePrefix() . $branch;
        }

        $upstream_repository = 'remotes/pr/' . $pull_request_number . '/head';
        $this->GitCmdExecuter->checkout($upstream_repository);
        $this->GitCmdExecuter->checkout($upstream_repository, ['-b', $branch]);
    }

    /**
     *  prTrack
     *
     * @param string $pull_request_number
     *
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @return void
     * @access      public
     */
    public function prTrack($pull_request_number)
    {
        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();
        $this->GitCmdExecuter->fetchPullRequest();

        $repository = 'pullreq/' . $pull_request_number;
        $upstream_repository = 'remotes/pr/' . $pull_request_number . '/head';
        $this->GitCmdExecuter->checkout($upstream_repository, ['-b', $repository]);
    }

    /**
     *  pr pull
     *
     * @throws Exception
     * @return void
     * @access      public
     */
    public function prPull()
    {
        $branch = $this->getSelfBranchRef();
        $match = null;
        if (!mb_ereg('/pullreq/([0-9]+)', $branch, $match)) {
            return;
        }

        $pull_request_number = $match[1];

        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();
        $this->GitCmdExecuter->fetchPullRequest();

        $upstream_repository = 'pull/' . $pull_request_number . '/head';
        $this->GitCmdExecuter->pull('upstream', $upstream_repository);
    }

    /**
     *  pr merge
     *
     * @access      public
     * @param  string $pull_request_number
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @return void
     */
    public function prMerge($pull_request_number)
    {
        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();
        $this->GitCmdExecuter->fetchPullRequest();

        $upstream_repository = 'pull/' . $pull_request_number . '/head';
        $this->GitCmdExecuter->pull('upstream', $upstream_repository);
    }
}