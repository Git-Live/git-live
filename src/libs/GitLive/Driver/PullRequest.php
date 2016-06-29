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

        default:
            $this->Driver('Help')->help();
        break;
        }
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
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p', 'deploy'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
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
        $branch = $this->getSelfBranch();
        if (!mb_ereg('/pullreq/([0-9]+)', $branch, $match)) {
            return;
        }

        $pull_request_number = $match[1];

        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p', 'deploy'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
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
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p', 'deploy'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
        $this->GitCmdExecuter->fetchPullRequest();

        $upstream_repository = 'pull/'.$pull_request_number.'/head';
        $this->GitCmdExecuter->pull('upstream', $upstream_repository);
    }

    /* ----------------------------------------- */
}
