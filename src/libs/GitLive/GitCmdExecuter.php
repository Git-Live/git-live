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

    /**
     * +-- git configを実行する
     *
     * @access      public
     * @param       array $options OPTIONAL:null
     * @return      string
     */
    public function config(array $options = null)
    {
        $cmd = $this->createCmd('config', $options);

        return $this->exec($cmd, true);
    }
    /* ----------------------------------------- */

    /**
     * +-- git tagを実行する
     *
     * @access      public
     * @param       array $options OPTIONAL:null
     * @return      string
     */
    public function tag(array $options = null)
    {
        $cmd = $this->createCmd('tag', $options);

        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- git cloneを実行する
     *
     * @access      public
     * @param       array $options OPTIONAL:null
     * @return      string
     */
    public function copy(array $options = null)
    {
        $cmd = $this->createCmd('clone', $options);

        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- git remoteを実行する
     *
     * @access      public
     * @param       array $options OPTIONAL:null
     * @return      string
     */
    public function remote(array $options = null)
    {
        $cmd = $this->createCmd('remote', $options);

        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- git statusを実行する
     *
     * @access      public
     * @param       array $options OPTIONAL:null
     * @return      string
     */
    public function status(array $options = null)
    {
        $cmd = $this->createCmd('status', $options);

        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- git diffを実行する
     *
     * @access      public
     * @param       array $options OPTIONAL:null
     * @return      string
     */
    public function diff(array $options = null)
    {
        $cmd = $this->createCmd('diff', $options);

        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- git mergeを実行する
     *
     * @access      public
     * @param       var_text $branch
     * @param       array $options OPTIONAL:null
     * @return      string
     */
    public function merge($branch, array $options = null)
    {
        $cmd = $this->createCmd('merge', $options);
        $cmd .= ' '.$branch;
        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- git fetchを実行する
     *
     * @access      public
     * @param       array $options OPTIONAL:null
     * @return      string
     */
    public function fetch(array $options = null)
    {
        $cmd = $this->createCmd('fetch', $options);
        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- git cleanを実行する
     *
     * @access      public
     * @param       array $options OPTIONAL:null
     * @return      string
     */
    public function clean(array $options = null)
    {
        $cmd = 'git clean -df';
        if ($options) {
            $cmd = $this->createCmd('clean', $options);
        }
        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- git resetを実行する
     *
     * @access      public
     * @param       array $options OPTIONAL:null
     * @return      string
     */
    public function reset(array $options = null)
    {
        $cmd = 'git reset --hard HEAD';
        if ($options) {
            $cmd = $this->createCmd('reset', $options);
        }
        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- git checkoutを実行する
     *
     * @access      public
     * @param       var_text $branch
     * @param       array $options OPTIONAL:null
     * @return      string
     */
    public function checkout($branch, array $options = null)
    {
        $cmd = $this->createCmd('checkout', $options);
        $cmd .= ' '.$branch;

        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- git branchを実行する
     *
     * @access      public
     * @param       array $options OPTIONAL:null
     * @return      string
     */
    public function branch(array $options = null)
    {
        $cmd = $this->createCmd('branch', $options);
        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- git pullを実行する
     *
     * @access      public
     * @param       string $remote
     * @param       string $branch OPTIONAL:''
     * @return      string
     */
    public function pull($remote, $branch = '')
    {
        $cmd = $this->createCmd('pull', array($remote, $branch));
        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- git pushを実行する
     *
     * @access      public
     * @param       string $remote
     * @param       string $branch OPTIONAL:''
     * @return      string
     */
    public function push($remote, $branch = '')
    {
        $cmd = $this->createCmd('push', array($remote, $branch));
        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- tagをpushする
     *
     * @access      public
     * @param       string $remote
     * @return      string
     */
    public function tagPush($remote)
    {
        $cmd = $this->createCmd('push', array($remote, '--tags'));
        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- git logをsimpleに実行する
     *
     * @access      public
     * @param       string $left
     * @param       string $right
     * @param       array $option OPTIONAL:NULL
     * @return      string
     */
    public function log($left, $right, array $option = NULL)
    {
        if (empty($option)) {
            $cmd = $this->createCmd('log', array('--pretty=fuller', '--name-status', $left.'..'.$right));
        } else {
            $cmd = $this->createCmd('log', array('--pretty=fuller', '--name-status', join(' ', $option), $left.'..'.$right));
        }

        return $this->exec($cmd);
    }
    /* ----------------------------------------- */

    /**
     * +-- コマンドの作成
     *
     * @access      protected
     * @param       string $git_task
     * @param       array $options OPTIONAL:null
     * @return      string
     */
    protected function createCmd($git_task, array $options = null)
    {
        $cmd = 'git '.$git_task;
        if (count($options)) {
            $cmd .= ' '.join(' ', $options);
        }
        return $cmd;
    }
    /* ----------------------------------------- */
}
