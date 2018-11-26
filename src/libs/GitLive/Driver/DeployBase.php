<?php
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

namespace GitLive\Driver;

use App;

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
     *  リリースを開く
     *
     * @access      public
     * @param null $release_rep
     * @return void
     * @throws Exception
     * @throws \ReflectionException
     */
    public function buildOpen($release_rep = null)
    {
        if ($this->isReleaseOpen()) {
            throw new Exception(sprintf(__('Already %1$s opened.'), 'release'));
        } elseif ($this->isHotfixOpen()) {
            throw new Exception(sprintf(__('Already %1$s opened.'), 'hotfix'));
        }

        $repository = $this->GitCmdExecuter->branch(['-a']);
        $repository = explode("\n", trim($repository));
        foreach ($repository as $value) {
            if (strpos($value, 'remotes/' . $this->deploy_repository_name . '/' . $this->prefix) !== false) {
                throw new Exception(sprintf(__('Already %1$s opened.'), static::MODE) . "\n" . $value);
            }
        }

        $release_rep = $this->prefix . ($release_rep ?: date('Ymdhis'));

        $this->GitCmdExecuter->checkout('upstream/' . $this->develop_branch);
        $this->GitCmdExecuter->checkout($release_rep, ['-b']);

        $this->GitCmdExecuter->push('upstream', $release_rep);
        $this->GitCmdExecuter->push($this->deploy_repository_name, $release_rep);
    }

    /**
     *  リリースが空いているかどうか
     *
     * @access      public
     * @return bool
     * @throws \ReflectionException
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
     *  使用しているリリースRepositoryの取得
     *
     * @access      public
     * @return string
     * @throws Exception
     * @throws \ReflectionException
     */
    public function getReleaseRepository()
    {
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();
        $release_prefix = App::make(ConfigDriver::class)->releasePrefix();


        $branches = $this->GitCmdExecuter->branch(['-a']);
        $branches = explode("\n", trim($branches));

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
     *  ホットフィクスが空いているかどうか
     *
     * @access      public
     * @return bool
     * @throws \ReflectionException
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
     *  使用しているhot fix Repositoryの取得
     *
     * @access      public
     * @return string
     * @throws Exception
     * @throws \ReflectionException
     */
    public function getHotfixRepository()
    {
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();
        $release_prefix = App::make(ConfigDriver::class)->hotfixPrefix();


        $branches = $this->GitCmdExecuter->branch(['-a']);
        $branches = explode("\n", trim($branches));

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
     *  リリースタグを指定してリリース開く
     *
     * @access      public
     * @param       string $tag_name
     * @return      void
     * @throws Exception
     * @throws \ReflectionException
     */
    public function buildOpenWithReleaseTag($tag_name)
    {
        if ($this->isReleaseOpen()) {
            throw new Exception(sprintf(__('Already %1$s opened.'), 'release'));
        } elseif ($this->isHotfixOpen()) {
            throw new Exception(sprintf(__('Already %1$s opened.'), 'hotfix'));
        }

        $repository = $this->GitCmdExecuter->branch(['-a']);
        $repository = explode("\n", trim($repository));

        foreach ($repository as $value) {
            if (strpos($value, 'remotes/' . $this->deploy_repository_name . '/release/') !== false) {
                throw new Exception(sprintf(__('Already %1$s opened.'), 'release') . "\n" . $value);
            }
        }

        $release_rep = 'release/' . date('Ymdhis');

        $this->GitCmdExecuter->checkout('upstream/' . $this->develop_branch);
        $this->GitCmdExecuter->checkout('', ['-b', $release_rep, 'refs/tags/' . $tag_name]);

        $this->GitCmdExecuter->push('upstream', $release_rep);
        $this->GitCmdExecuter->push($this->deploy_repository_name, $release_rep);
    }

    /**
     *  誰かが開けたリリースをトラックする
     *
     * @access      public
     * @return void
     * @throws Exception
     * @throws \ReflectionException
     */
    public function buildTrack()
    {
        if (!$this->isBuildOpen()) {
            throw new Exception(sprintf(__('%1$s is not open.'), static::MODE));
        }

        $repo = $this->getBuildRepository();
        $this->deployTrack($repo);

        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->pull($this->deploy_repository_name, $repo);
    }

    /**
     * @return bool
     */
    public abstract function isBuildOpen();

    /**
     * @return string
     */
    public abstract function getBuildRepository();

    /**
     *  DeployブランチをTrackする
     *
     * @access      public
     * @param  string $repo
     * @return void
     * @throws \ReflectionException
     */
    public function deployTrack($repo)
    {
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();

        if ($this->isBranchExits($repo)) {
            $this->GitCmdExecuter->checkout($repo, []);
        } else {
            $this->GitCmdExecuter->checkout('remote/' . $deploy_repository_name . '/' . $repo);
            $this->GitCmdExecuter->checkout($repo, ['-b']);
        }
    }

    /**
     *  誰かが開けたRELEASEをpullする
     *
     * @access      public
     * @return void
     * @throws Exception
     */
    public function buildPull()
    {
        if (!$this->isBuildOpen()) {
            throw new Exception(sprintf(__('%1$s is not open.'), static::MODE));
        }

        $repo = $this->getBuildRepository();
        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->pull($this->deploy_repository_name, $repo);
    }

    /**
     *  リリースの状態を確かめる
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
                $option = $with_merge_commit ? [] : ['--no-merges'];
                $res .= ($this->GitCmdExecuter->log($this->deploy_repository_name . '/' . $this->master_branch, $repo, $option));
            }

            $res .= (sprintf(__('%1$s is open.'), static::MODE) . "\n");

            return $res;
        }

        return (sprintf(__('%1$s is close.'), static::MODE) . "\n");
    }

    /**
     *  コードを各環境に反映する
     *
     * @access      public
     * @return void
     * @throws Exception
     * @throws \ReflectionException
     */
    public function buildSync()
    {
        if (!$this->isBuildOpen()) {
            throw new Exception(sprintf(__('%1$s is not open.'), static::MODE));
        }

        $repo = $this->getBuildRepository();

        $this->deploySync($repo);
    }

    /**
     *  DeployブランチにSyncする
     *
     * @access      public
     * @param  string $repo
     * @return void
     * @throws Exception
     * @throws \ReflectionException
     */
    public function deploySync($repo)
    {
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();

        $this->deployTrack($repo);

        $this->GitCmdExecuter->pull('deploy', $repo);
        $this->GitCmdExecuter->pull('upstream', $repo);

        $err = $this->GitCmdExecuter->status([$repo]);
        if (strpos(trim($err), 'nothing to commit') === false) {
            throw new Exception($err);
        }

        $this->GitCmdExecuter->push('upstream', $repo);
        $this->GitCmdExecuter->push($deploy_repository_name, $repo);
    }

    /**
     *  コードをupstreamに反映する
     *
     * @access      public
     * @return void
     * @throws Exception
     * @throws \ReflectionException
     */
    public function buildPush()
    {
        if (!$this->isBuildOpen()) {
            throw new Exception(sprintf(__('%1$s is not open.'), static::MODE));
        }

        $repo = $this->getBuildRepository();

        $this->deployPush($repo);
    }

    /**
     *  upstream に pushする
     *
     * @access      public
     * @param  string $repo
     * @return void
     * @throws Exception
     * @throws \ReflectionException
     */
    public function deployPush($repo)
    {
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();

        if (!$this->isBranchExits($repo)) {
            throw new Exception('undefined ' . $repo);
        }

        $this->GitCmdExecuter->checkout($repo);

        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->pull($deploy_repository_name, $repo);

        $err = $this->GitCmdExecuter->status([$repo]);
        if (strpos($err, 'nothing to commit') === false) {
            throw new Exception($err);
        }

        $this->GitCmdExecuter->push('upstream', $repo);
    }

    /**
     *  リリースを取り下げる
     *
     * @access      public
     * @param bool $remove_local OPTIONAL:false
     * @return void
     *
     * @throws Exception
     */
    public function buildDestroy($remove_local = false)
    {
        if (!$this->isBuildOpen()) {
            throw new Exception(sprintf(__('%1$s is not open.'), static::MODE));
        }

        $repo = $this->getBuildRepository();
        $this->deployDestroy($repo, static::MODE, $remove_local);
    }

    /**
     *  削除
     *
     * @access      public
     * @param  string $repo
     * @param  string $mode
     * @param bool    $remove_local OPTIONAL:false
     * @return void
     * @throws Exception
     */
    public function deployDestroy($repo, $mode, $remove_local = false)
    {
        // Repositoryの掃除
        $this->GitCmdExecuter->push('deploy', ':' . $repo);
        $this->GitCmdExecuter->push('upstream', ':' . $repo);

        if ($mode === 'hotfix' && strpos($repo, $this->Driver(ConfigDriver::class)->hotfixPrefix()) === false) {
            throw new Exception($repo . __(' is not hotfix branch.'));
        } elseif ($mode === 'release' && strpos($repo, $this->Driver(ConfigDriver::class)->releasePrefix()) === false) {
            throw new Exception($repo . __(' is not release branch.'));
        } elseif ($mode !== 'hotfix' && $mode !== 'release') {
            throw new Exception($mode . __(' is not deploy mode.'));
        }

        // ローカルブランチの削除
        if ($remove_local) {
            $this->clean();
            $this->GitCmdExecuter->branch(['-d', $repo]);
            $this->GitCmdExecuter->checkout($this->Driver(ConfigDriver::class)->develop());
        }
    }

    /**
     *  リリースを閉じる
     *
     * @access      public
     * @param bool $force OPTIONAL:false
     * @param null $tag_name
     * @return void
     *
     * @throws Exception
     * @throws \ReflectionException
     */
    public function buildClose($force = false, $tag_name = null)
    {
        if (!$this->isBuildOpen()) {
            throw new Exception(sprintf(__('%1$s is not open.'), static::MODE));
        }

        $repo = $this->getBuildRepository();
        $this->deployEnd($repo, static::MODE, $force, $tag_name);
    }

    /**
     *  hotfixCloseとreleaseClose共通処理
     *
     * @access      public
     * @param  string $repo
     * @param  string $mode
     * @param bool    $force OPTIONAL:false
     * @param null    $tag_name
     * @return void
     * @throws Exception
     * @throws \ReflectionException
     */
    public function deployEnd($repo, $mode, $force = false, $tag_name = null)
    {
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();
        $master_branch = App::make(ConfigDriver::class)->master();
        $develop = App::make(ConfigDriver::class)->develop();


        // マスターのマージ
        $this->GitCmdExecuter->checkout($deploy_repository_name . '/' . $master_branch);
        $this->GitCmdExecuter->branch(['-D', $master_branch]);
        $this->GitCmdExecuter->checkout($master_branch, ['-b']);

        if ($this->getSelfBranchRef() !== 'refs/heads/' . $master_branch) {
            $this->GitCmdExecuter->checkout($repo);
            $error_msg = sprintf(__('%1$s close is failed.'), $mode) . "\n" .
                sprintf(__('%1$s branch has a commit that is not on the %2$s branch'), 'Master', ucwords($mode));
            throw new Exception($error_msg);
        }

        if (!$this->patchApplyCheck('deploy/' . $repo)) {
            $error_msg = sprintf(__('%1$s close is failed.'), $mode) . "\n" .
                sprintf(__('%1$s branch has a commit that is not on the %2$s branch'), 'Master', ucwords($mode));
            throw new Exception($error_msg);
        }

        $this->GitCmdExecuter->merge('deploy/' . $repo);
        $diff = $this->GitCmdExecuter->diff([$deploy_repository_name . '/' . $repo, $master_branch]);

        if (strlen($diff) !== 0) {
            $error_msg = $diff . "\n" . sprintf(__('%1$s close is failed.'), $mode);
            throw new Exception($error_msg);
        }

        $this->GitCmdExecuter->push('upstream', $master_branch);
        $this->GitCmdExecuter->push($deploy_repository_name, $master_branch);

        // developのマージ
        $this->GitCmdExecuter->checkout('upstream/' . $develop);
        $this->GitCmdExecuter->branch(['-D', $develop]);
        $this->GitCmdExecuter->checkout($develop, ['-b']);

        if ($this->getSelfBranchRef() !== 'refs/heads/' . $develop) {
            $this->GitCmdExecuter->checkout($repo);
            $error_msg = sprintf(__('%1$s close is failed.'), $mode) . "\n" .
                sprintf(__('%1$s branch has a commit that is not on the %2$s branch'), 'Develop', ucwords($mode));
            throw new Exception($error_msg);
        }

        $this->GitCmdExecuter->merge($deploy_repository_name . '/' . $repo);

        if ($mode === 'release' && !$force) {
            $diff = $this->GitCmdExecuter->diff([$deploy_repository_name . '/' . $repo, $develop]);
        }

        if (strlen($diff) !== 0) {
            $error_msg = sprintf(__('%1$s close is failed.'), $mode) . "\n" .
                sprintf(__('%1$s branch has a commit that is not on the %2$s branch'), 'Develop', ucwords($mode));
            throw new Exception($error_msg);
        }

        $this->GitCmdExecuter->push('upstream', $develop);

        // Repositoryの掃除
        $this->GitCmdExecuter->push($deploy_repository_name, ':' . $repo);
        $this->GitCmdExecuter->push('upstream', ':' . $repo);
        $this->GitCmdExecuter->branch(['-d', $repo]);

        // タグ付け
        $this->GitCmdExecuter->fetch(['upstream']);
        $this->GitCmdExecuter->checkout('upstream/master');

        if (empty($tag_name)) {
            list(, $tag_name) = explode('/', $repo);
            $tag_name = 'r' . $tag_name;
        }

        $this->GitCmdExecuter->tag([$tag_name]);
        $this->GitCmdExecuter->tagPush('upstream');

        $this->GitCmdExecuter->checkout($develop);
    }

    /**
     *  releaseコマンド、hotfixコマンドが使用できるかどうか
     *
     * @access      public
     * @return void
     * @throws Exception
     * @throws \ReflectionException
     */
    public function enableRelease()
    {
        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();
        $remote = $this->GitCmdExecuter->remote();
        $remote = explode("\n", trim($remote));
        $res = array_search($deploy_repository_name, $remote) !== false;
        if ($res === false) {
            throw new Exception(
                sprintf(__('Add a remote repository %s.'), $deploy_repository_name)
            );
        }
    }


}
