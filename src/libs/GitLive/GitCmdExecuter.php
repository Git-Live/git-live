<?php
/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveFlow
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
 * @package    GitLive
 * @subpackage GitLiveFlow
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
     * @access      public
     * @return string
     */
    public function fetchPullRequest()
    {
        $cmd = "git fetch upstream '+refs/pull/*:refs/remotes/pr/*'";
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    /* ----------------------------------------- */

    public function tag(array $options = NULL)
    {
        $cmd = 'git tag ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function copy(array $options = NULL)
    {
        $cmd = 'git clone ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function remote(array $options = NULL)
    {
        $cmd = 'git remote ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function status(array $options = NULL)
    {
        $cmd = 'git status ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function diff(array $options = NULL)
    {
        $cmd = 'git diff ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }

    public function merge($branch, array $options = NULL)
    {
        $cmd = 'git merge ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $cmd .= ' '.$branch;
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }

    public function fetch(array $options = NULL)
    {
        $cmd = 'git fetch ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }

    public function checkout($branch, array $options = NULL)
    {
        $cmd = 'git checkout ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $cmd .= ' '.$branch;
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function branch(array $options = NULL)
    {
        $cmd = 'git branch ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function pull($remote, $branch = '')
    {
        $cmd = 'git pull ';

        $cmd .= ' '.$remote.' '.$branch;
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }

    public function push($remote, $branch = '')
    {
        $cmd = 'git push ';

        $cmd .= ' '.$remote.' '.$branch;
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function tagPush($remote)
    {
        $cmd = 'git push ';

        $cmd .= ' '.$remote.' --tags';
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }

    public function log($left, $right, $option = '')
    {
        $cmd = 'git log --pretty=fuller --name-status '
            .$option.' '.$left.'..'.$right;
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }

}
