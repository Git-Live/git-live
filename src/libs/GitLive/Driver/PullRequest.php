<?php
/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
namespace GitLive\Driver;

/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
class PullRequest extends DriverBase
{

    /**
     * +-- プルリクエストの管理
     *
     * @access      public
     * @return void
     */
    public function pr()
    {
        $argv = $this->getArgv();
        if (!isset($argv[2])) {
            $this->Driver('Help')->help();

            return;
        }

        switch ($argv[2]) {
        case 'track':
            if (!isset($argv[3])) {
                $this->Driver('Help')->help();

                return;
            }

            $this->prTrack($argv[3]);
        break;
        case 'pull':
            $this->prPull();
        break;
        case 'merge':
            if (!isset($argv[3])) {
                $this->Driver('Help')->help();

                return;
            }

            $this->prMerge($argv[3]);
        break;

        case 'feature':
            if (!isset($argv[3])) {
                $this->Driver('Help')->help();
                return;
            }

            if ($argv[3] === 'start') {
                if (!isset($argv[5])) {
                    $this->Driver('Help')->help();
                    return;
                }

                $this->featureStart($argv[4], $argv[5]);
                return;
            } elseif ($argv[3] === 'start-soft') {
                if (!isset($argv[5])) {
                    $this->Driver('Help')->help();
                    return;
                }

                $this->featureStartSoft($argv[4], $argv[5]);
                return;
            }

            $this->Driver('Help')->help();

        break;

        default:
            $this->Driver('Help')->help();
        break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- pr feature start
     *
     * @access      public
     * @param  var_text $pull_request_number
     * @param  var_text $repository
     * @return void
     */
    public function featureStart($pull_request_number, $repository)
    {
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        $this->GitCmdExecuter->fetchPullRequest();

        if (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }

        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout($repository, array('-b'));
        $self_repository = $this->getSelfBranchRef();

        if (!'refs/heads/'.$repository === $self_repository) {
            throw new \GitLive\exception(_('feature の作成に失敗'));
        }

        $upstream_repository = 'pull/'.$pull_request_number.'/head';
        $this->GitCmdExecuter->pull('upstream', $upstream_repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- pr feature start-soft
     *
     * @access      public
     * @param  var_text $pull_request_number
     * @param  var_text $repository
     * @return void
     */
    public function featureStartSoft($pull_request_number, $repository)
    {
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        $this->GitCmdExecuter->fetchPullRequest();

        if (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }

        $upstream_repository = 'remotes/pr/'.$pull_request_number.'/head';
        $this->GitCmdExecuter->checkout($upstream_repository);
        $this->GitCmdExecuter->checkout($upstream_repository, array('-b', $repository));
    }
    /* ----------------------------------------- */

    /**
     * +-- prTrack
     *
     * @param var_text $pull_request_number
     *
     * @access      public
     * @return void
     */
    public function prTrack($pull_request_number)
    {
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        $this->GitCmdExecuter->fetchPullRequest();

        $repository          = 'pullreq/'.$pull_request_number;
        $upstream_repository = 'remotes/pr/'.$pull_request_number.'/head';
        $this->GitCmdExecuter->checkout($upstream_repository, array('-b', $repository));
    }
    /* ----------------------------------------- */

    /**
     * +-- pr pull
     *
     * @param var_text $pull_request_number
     *
     * @access      public
     * @return void
     */
    public function prPull()
    {
        $branch = $this->getSelfBranchRef();
        if (!mb_ereg('/pullreq/([0-9]+)', $branch, $match)) {
            return;
        }

        $pull_request_number = $match[1];

        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        $this->GitCmdExecuter->fetchPullRequest();

        $upstream_repository = 'pull/'.$pull_request_number.'/head';
        $this->GitCmdExecuter->pull('upstream', $upstream_repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- pr merge
     *
     * @access      public
     * @param  var_text $pull_request_number
     * @return void
     */
    public function prMerge($pull_request_number)
    {
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        $this->GitCmdExecuter->fetchPullRequest();

        $upstream_repository = 'pull/'.$pull_request_number.'/head';
        $this->GitCmdExecuter->pull('upstream', $upstream_repository);
    }
    /* ----------------------------------------- */
}
