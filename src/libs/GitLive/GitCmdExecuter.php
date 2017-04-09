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
namespace GitLive;

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
class GitCmdExecuter extends GitBase
{
    /**
     * +--
     *
     * @return string
     */
    public function fetchPullRequest()
    {
        $cmd = "git fetch upstream '+refs/pull/*:refs/remotes/pr/*'";

        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    public function config(array $options = null)
    {
        $cmd = $this->createCmd('config', $options);

        return $this->exec($cmd, true);
    }

    public function tag(array $options = null)
    {
        $cmd = $this->createCmd('tag', $options);

        return $this->exec($cmd);
    }

    public function copy(array $options = null)
    {
        $cmd = $this->createCmd('clone', $options);

        return $this->exec($cmd);
    }
    public function remote(array $options = null)
    {
        $cmd = $this->createCmd('remote', $options);

        return $this->exec($cmd);
    }
    public function status(array $options = null)
    {
        $cmd = $this->createCmd('status', $options);

        return $this->exec($cmd);
    }
    public function diff(array $options = null)
    {
        $cmd = $this->createCmd('diff', $options);

        return $this->exec($cmd);
    }

    public function merge($branch, array $options = null)
    {
        $cmd = $this->createCmd('merge', $options);
        $cmd .= ' '.$branch;
        return $this->exec($cmd);
    }

    public function fetch(array $options = null)
    {
        $cmd = $this->createCmd('fetch', $options);
        return $this->exec($cmd);
    }

    public function clean(array $options = null)
    {
        $cmd = 'git clean -df';
        if ($options) {
            $cmd = $this->createCmd('clean', $options);
        }
        return $this->exec($cmd);
    }

    public function reset(array $options = null)
    {
        $cmd = 'git reset --hard HEAD';
        if ($options) {
            $cmd = $this->createCmd('reset', $options);
        }
        return $this->exec($cmd);
    }

    public function checkout($branch, array $options = null)
    {
        $cmd = $this->createCmd('checkout', $options);
        $cmd .= ' '.$branch;

        return $this->exec($cmd);
    }
    public function branch(array $options = null)
    {
        $cmd = $this->createCmd('branch', $options);
        return $this->exec($cmd);
    }
    public function pull($remote, $branch = '')
    {
        $cmd = $this->createCmd('pull', array($remote, $branch));
        return $this->exec($cmd);
    }

    public function push($remote, $branch = '')
    {
        $cmd = $this->createCmd('push', array($remote, $branch));
        return $this->exec($cmd);
    }
    public function tagPush($remote)
    {
        $cmd = $this->createCmd('push', array($remote, '--tags'));
        return $this->exec($cmd);
    }

    public function log($left, $right, $option = '')
    {
        if (empty($option)) {
            $cmd = $this->createCmd('log', array('--pretty=fuller', '--name-status', $left.'..'.$right));
        } else {
            $cmd = $this->createCmd('log', array('--pretty=fuller', '--name-status', $option, $left.'..'.$right));
        }

        return $this->exec($cmd);
    }

    protected function createCmd($git_task, array $options = null)
    {
        $cmd = 'git '.$git_task;
        if (count($options)) {
            $cmd .= ' '.join(' ', $options);
        }
        return $cmd;
    }
}
