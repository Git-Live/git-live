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

use App;
use GitLive\GitLive;
use GitLive\Support\GitCmdExecutor;
use GitLive\Support\SystemCommandInterface;
use JapaneseDate\DateTime;

/**
 * Class DeployBase
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
abstract class DeployBase extends DriverBase
{
    /**
     * @var string
     */
    const MODE = '';
    /**
     * @var string
     */
    public $prefix;
    /**
     * @var string
     */
    public $master_branch;
    /**
     * @var string
     */
    public $develop_branch;
    /**
     * @var string
     */
    public $deploy_repository_name;

    /**
     * DeployBase constructor.
     * @param GitLive                $GitLive
     * @param GitCmdExecutor         $gitCmdExecutor
     * @param SystemCommandInterface $command
     */
    public function __construct(GitLive $GitLive, GitCmdExecutor $gitCmdExecutor, SystemCommandInterface $command)
    {
        parent::__construct($GitLive, $gitCmdExecutor, $command);

        $this->deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();
        $this->develop_branch = App::make(ConfigDriver::class)->develop();
        $this->master_branch = App::make(ConfigDriver::class)->master();
    }

    /**
     * @throws Exception
     */
    public function boot()
    {
        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();
        $this->Driver(FetchDriver::class)->deploy($this->deploy_repository_name);

        $this->enableRelease();
    }

    /**
     *  Open a build branch.
     *
     * @access      public
     * @param null $release_rep
     * @throws Exception
     * @return void
     */
    public function buildOpen($release_rep = null)
    {
        if ($this->isReleaseOpen()) {
            throw new Exception(sprintf(__('Already %s opened.'), ReleaseDriver::MODE));
        }
        if ($this->isHotfixOpen()) {
            throw new Exception(sprintf(__('Already %s opened.'), HotfixDriver::MODE));
        }

        $repository = $this->GitCmdExecutor->branch(['-a']);
        $repository = explode("\n", trim($repository));
        foreach ($repository as $value) {
            if (strpos($value, 'remotes/' . $this->deploy_repository_name . '/' . $this->prefix) !== false) {
                throw new Exception(sprintf(__('Already %s opened.'), static::MODE) . "\n" . $value);
            }
        }

        $release_rep = $this->prefix . ($release_rep ?: DateTime::now()->format('Ymdhis'));

        if (static::MODE === ReleaseDriver::MODE) {
            $this->GitCmdExecutor->checkout('upstream/' . $this->develop_branch);
        } elseif (static::MODE === HotfixDriver::MODE) {
            $this->GitCmdExecutor->checkout('upstream/' . $this->master_branch);
        }

        $this->GitCmdExecutor->checkout($release_rep, ['-b']);

        $this->GitCmdExecutor->push('upstream', $release_rep);
        $this->GitCmdExecutor->push($this->deploy_repository_name, $release_rep);
    }

    /**
     *  Whether the release is open or not
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isReleaseOpen()
    {
        try {
            $this->getReleaseRepository();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     *  Get a current release branch
     *
     * @access      public
     * @throws Exception
     * @return string
     */
    public function getReleaseRepository()
    {
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();
        $release_prefix = App::make(ConfigDriver::class)->releasePrefix();

        $branches = $this->Driver(BranchDriver::class)->branchListAll();

        $release_branch = false;
        foreach ($branches as $branch) {
            $match = null;
            if (mb_ereg('remotes/' . $deploy_repository_name . '/(' . $release_prefix . '[^/]*$)', $branch, $match)) {
                $release_branch = $match[1];

                break;
            }
        }

        if (!$release_branch) {
            throw new Exception('Release is not open.');
        }

        return trim($release_branch);
    }

    /**
     *  Whether the hotfix is open or not
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isHotfixOpen()
    {
        try {
            $this->getHotfixRepository();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     *  Get a current hot-fix branch
     *
     * @access      public
     * @throws Exception
     * @return string
     */
    public function getHotfixRepository()
    {
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();
        $release_prefix = App::make(ConfigDriver::class)->hotfixPrefix();

        $branches = $this->Driver(BranchDriver::class)->branchListAll();

        $release_branch = false;
        foreach ($branches as $branch) {
            $match = null;
            if (mb_ereg('remotes/' . $deploy_repository_name . '/(' . $release_prefix . '[^/]*$)', $branch, $match)) {
                $release_branch = $match[1];

                break;
            }
        }

        if (!$release_branch) {
            throw new Exception('Hotfix is not open.');
        }

        return trim($release_branch);
    }

    /**
     *  Open a build branch specify the tag
     *
     * @access      public
     * @param       string $tag_name
     * @param null         $release_rep
     * @throws Exception
     * @return      void
     */
    public function buildOpenWithReleaseTag($tag_name, $release_rep = null)
    {
        if ($this->isReleaseOpen()) {
            throw new Exception(sprintf(__('Already %s opened.'), ReleaseDriver::MODE));
        }
        if ($this->isHotfixOpen()) {
            throw new Exception(sprintf(__('Already %s opened.'), HotfixDriver::MODE));
        }

        $branches = $this->Driver(BranchDriver::class)->branchListAll();

        foreach ($branches as $value) {
            if (strpos($value, 'remotes/' . $this->deploy_repository_name . '/release/') !== false) {
                throw new Exception(sprintf(__('Already %s opened.'), ReleaseDriver::MODE) . "\n" . $value);
            }
        }

        $release_rep = $this->prefix . ($release_rep ?: DateTime::now()->format('Ymdhis'));

        $this->GitCmdExecutor->checkout('upstream/' . $this->develop_branch);
        $this->GitCmdExecutor->checkout('', ['-b', $release_rep, 'refs/tags/' . $tag_name]);

        $this->GitCmdExecutor->push('upstream', $release_rep);
        $this->GitCmdExecutor->push($this->deploy_repository_name, $release_rep);
    }

    /**
     *  Track an all build branch.
     *
     * @access      public
     * @throws Exception
     * @return void
     */
    public function buildTrack()
    {
        if (!$this->isBuildOpen()) {
            throw new Exception(sprintf(__('%s is not open.'), static::MODE));
        }

        $repo = $this->getBuildRepository();
        $this->deployTrack($repo);

        $this->GitCmdExecutor->pull('upstream', $repo);
        $this->GitCmdExecutor->pull($this->deploy_repository_name, $repo);
    }

    /**
     * @return bool
     */
    abstract public function isBuildOpen();

    /**
     * @return string
     */
    abstract public function getBuildRepository();

    /**
     *  Track a deploy branch.
     *
     * @access      public
     * @param  string $repo
     * @throws Exception
     * @return void
     */
    public function deployTrack($repo)
    {
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();

        if ($this->isBranchExists($repo)) {
            $this->GitCmdExecutor->checkout($repo, []);
        } else {
            $this->GitCmdExecutor->checkout('remote/' . $deploy_repository_name . '/' . $repo);
            $this->GitCmdExecutor->checkout($repo, ['-b']);
        }
    }

    /**
     * Pull an upstream build branch.
     *
     * @access      public
     * @throws Exception
     * @return void
     */
    public function buildPull()
    {
        if (!$this->isBuildOpen()) {
            throw new Exception(sprintf(__('%s is not open.'), static::MODE));
        }

        $repo = $this->getBuildRepository();
        $this->GitCmdExecutor->pull('upstream', $repo);
        $this->GitCmdExecutor->pull($this->deploy_repository_name, $repo);
    }

    /**
     * Check the state of build.
     *
     * @access      public
     * @param       bool $ck_only           OPTIONAL:false
     * @param       bool $with_merge_commit OPTIONAL:false
     * @return string
     */
    public function buildState($ck_only = false, $with_merge_commit = false)
    {
        $res = '';

        if ($this->isBuildOpen()) {
            if (!$ck_only) {
                $repo = $this->getBuildRepository();

                $option[] = '--pretty=fuller';
                $option[] = '--name-status';
                if (!$with_merge_commit) {
                    $option[] = '--no-merges';
                }
                $res .= ($this->GitCmdExecutor->log($this->deploy_repository_name . '/' . $this->master_branch, $repo, $option, false, false, true));
            }

            $res .= (sprintf(__('%s is open.'), static::MODE) . "\n");

            return $res;
        }

        return (sprintf(__('%s is close.'), static::MODE) . "\n");
    }

    /**
     *  Sync a build branch to upstream and deploy.
     *
     * @access      public
     * @throws Exception
     * @return void
     */
    public function buildSync()
    {
        if (!$this->isBuildOpen()) {
            throw new Exception(sprintf(__('%s is not open.'), static::MODE));
        }

        $repo = $this->getBuildRepository();

        $this->deploySync($repo);
    }

    /**
     *  push a deploy branch to upstream.
     *
     * @access      public
     * @throws Exception
     * @return void
     */
    public function buildPush()
    {
        if (!$this->isBuildOpen()) {
            throw new Exception(sprintf(__('%s is not open.'), static::MODE));
        }

        $repo = $this->getBuildRepository();

        $this->upstreamPush($repo);
    }

    /**
     *  Withdraw release
     *
     * @access      public
     * @param bool $remove_local OPTIONAL:false
     * @throws Exception
     * @return void
     */
    public function buildDestroy($remove_local = false)
    {
        if (!$this->isBuildOpen()) {
            throw new Exception(sprintf(__('%s is not open.'), static::MODE));
        }

        $repo = $this->getBuildRepository();
        $this->deployDestroy($repo, static::MODE, $remove_local);
    }

    /**
     *  Close a build branch.
     *
     * @access      public
     * @param bool $force OPTIONAL:false
     * @param null $tag_name
     * @throws Exception
     * @return void
     */
    public function buildClose($force = false, $tag_name = null)
    {
        if (!$this->isBuildOpen()) {
            throw new Exception(sprintf(__('%s is not open.'), static::MODE));
        }

        $repo = $this->getBuildRepository();
        $this->deployEnd($repo, static::MODE, $force, $tag_name);
    }

    /**
     *  Whether release command, hotfix command is available
     *
     * @access      public
     * @throws Exception
     * @return void
     */
    public function enableRelease()
    {
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();
        $remote = $this->GitCmdExecutor->remote([], true);
        $remote = explode("\n", trim($remote));
        $res = array_search($deploy_repository_name, $remote, true) !== false;
        if ($res === false) {
            throw new Exception(
                sprintf(__('Add a remote repository %s.'), $deploy_repository_name)
            );
        }
    }

    /**
     *  Sync a build branch to deploy.
     *
     * @access      public
     * @param  string $repo
     * @throws Exception
     * @return void
     */
    protected function deploySync($repo)
    {
        $this->isCleanOrFail($repo);

        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();

        $this->deployTrack($repo);

        $this->GitCmdExecutor->pull($deploy_repository_name, $repo);
        $this->GitCmdExecutor->pull('upstream', $repo);

        $this->GitCmdExecutor->push('upstream', $repo);
        $this->GitCmdExecutor->push($deploy_repository_name, $repo);
    }

    /**
     *  push a deploy branch to upstream.
     *
     * @access      public
     * @param  string $repo
     * @throws Exception
     * @return void
     */
    protected function upstreamPush($repo)
    {
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();

        if (!$this->isBranchExists($repo)) {
            throw new Exception('undefined ' . $repo);
        }

        $this->GitCmdExecutor->checkout($repo);

        $this->GitCmdExecutor->pull('upstream', $repo);
        $this->GitCmdExecutor->pull($deploy_repository_name, $repo);

        $this->isCleanOrFail($repo);

        $this->GitCmdExecutor->push('upstream', $repo);
    }

    /**
     *  Delete a build branch.
     *
     * @access      public
     * @param  string $repo
     * @param  string $mode
     * @param bool    $remove_local OPTIONAL:false
     * @throws Exception
     * @return void
     */
    protected function deployDestroy($repo, $mode, $remove_local = false)
    {
        // Repositoryの掃除
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();

        $this->GitCmdExecutor->push($deploy_repository_name, ':' . $repo);
        $this->GitCmdExecutor->push('upstream', ':' . $repo);

        if ($mode === HotfixDriver::MODE && strpos($repo, $this->Driver(ConfigDriver::class)->hotfixPrefix()) === false) {
            throw new Exception(sprintf(__('%s is not hotfix branch.'), $repo));
        }
        if ($mode === ReleaseDriver::MODE && strpos($repo, $this->Driver(ConfigDriver::class)->releasePrefix()) === false) {
            throw new Exception(sprintf(__('%s is not release branch.'), $repo));
        }
        if ($mode !== HotfixDriver::MODE && $mode !== ReleaseDriver::MODE) {
            throw new Exception(sprintf(__('%s is not deploy mode.'), $repo));
        }

        $this->GitCmdExecutor->branch(['-d', $repo]);

        // ローカルブランチの削除
        if ($remove_local) {
            $this->clean();
            $this->GitCmdExecutor->checkout($this->Driver(ConfigDriver::class)->develop());
        }
    }

    /**
     *  Finish a build task.
     *
     * @access      public
     * @param  string $release_name
     * @param  string $mode
     * @param bool    $force OPTIONAL:false
     * @param null    $tag_name
     * @throws Exception
     * @return void
     */
    protected function deployEnd($release_name, $mode, $force = false, $tag_name = null)
    {
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();
        $master_branch = App::make(ConfigDriver::class)->master();
        $develop_branch = App::make(ConfigDriver::class)->develop();
        $deploy_prefix = $this->prefix;

        // マスターのマージ
        $this->GitCmdExecutor->checkout($deploy_repository_name . '/' . $master_branch);
        $this->GitCmdExecutor->branch(['-D', $master_branch]);
        $this->GitCmdExecutor->checkout($master_branch, ['-b']);

        if ($this->getSelfBranchRef() !== 'refs/heads/' . $master_branch) {
            $this->GitCmdExecutor->checkout($release_name);
            $error_msg = sprintf(__('%s close is failed.'), $mode) . "\n" .
                sprintf(__('%s branch has a commit that is not on the %2$s branch'), 'Master', ucwords($mode));

            throw new Exception($error_msg);
        }

        if (!$this->patchApplyCheck('deploy/' . $release_name)) {
            $error_msg = sprintf(__('%s close is failed.'), $mode) . "\n" .
                sprintf(__('%s branch has a commit that is not on the %2$s branch'), 'Master', ucwords($mode));

            throw new Exception($error_msg);
        }

        $this->GitCmdExecutor->merge('deploy/' . $release_name);
        $diff = $this->GitCmdExecutor->diff([$deploy_repository_name . '/' . $release_name, $master_branch]);

        if (strlen($diff) !== 0) {
            $error_msg = $diff . "\n" . sprintf(__('%s close is failed.'), $mode);

            throw new Exception($error_msg);
        }

        $this->GitCmdExecutor->push('upstream', $master_branch);
        $this->GitCmdExecutor->push($deploy_repository_name, $master_branch);

        // developのマージ
        $this->GitCmdExecutor->checkout('upstream/' . $develop_branch);
        $this->GitCmdExecutor->branch(['-D', $develop_branch]);
        $this->GitCmdExecutor->checkout($develop_branch, ['-b']);

        if ($this->getSelfBranchRef() !== 'refs/heads/' . $develop_branch) {
            $this->GitCmdExecutor->checkout($release_name);
            $error_msg = sprintf(__('%s close is failed.'), $mode) . "\n" .
                sprintf(__('%s branch has a commit that is not on the %2$s branch'), 'Develop', ucwords($mode));

            throw new Exception($error_msg);
        }

        $this->GitCmdExecutor->merge($deploy_repository_name . '/' . $release_name);

        if ($mode === ReleaseDriver::MODE && !$force) {
            $diff = $this->GitCmdExecutor->diff([$deploy_repository_name . '/' . $release_name, $develop_branch]);
        }

        if (strlen($diff) !== 0) {
            $error_msg = sprintf(__('%s close is failed.'), $mode) . "\n" .
                sprintf(__('%s branch has a commit that is not on the %2$s branch'), 'Develop', ucwords($mode));

            throw new Exception($error_msg);
        }

        $this->GitCmdExecutor->push('upstream', $develop_branch);

        // Repositoryの掃除
        $this->GitCmdExecutor->push($deploy_repository_name, ':' . $release_name);
        $this->GitCmdExecutor->push('upstream', ':' . $release_name);
        $this->GitCmdExecutor->branch(['-d', $release_name]);

        // タグ付け
        $this->GitCmdExecutor->fetch(['upstream']);
        $this->GitCmdExecutor->checkout('upstream/' . $master_branch);

        if (empty($tag_name)) {
            $tag_name = 'r' . $release_name;
            if (strpos($release_name, $deploy_prefix) === 0) {
                $tag_name = mb_substr($release_name, strlen($deploy_prefix));
                $tag_name = 'r' . $tag_name;
            }
        }

        $this->GitCmdExecutor->tag([$tag_name]);
        $this->GitCmdExecutor->tagPush('upstream');

        $this->GitCmdExecutor->checkout($develop_branch);
    }
}
