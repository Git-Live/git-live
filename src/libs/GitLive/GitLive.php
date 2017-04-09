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
class GitLive extends GitBase
{
    protected $GitCmdExecuter;
    protected $Driver;

    const VERSION_API                 = 'https://api.github.com/repos/Git-Live/git-live/releases/latest';

    /**
     * 更新チェックの間隔
     *
     * @access      protected
     * @var         int
     */
    protected $update_ck_span = 1200;

    /**
     * +-- コンストラクタ
     *
     * @access      public
     * @return void
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->GitCmdExecuter = new GitCmdExecuter();
    }

    /* ----------------------------------------- */

    /**
     * +-- デストラクタ
     *
     * @access      public
     * @return void
     * @codeCoverageIgnore
     */
    public function __destruct()
    {
        if (!$this->isQuiet() && $this->ckNewVersion()) {
            $this->ncecho("\n".__('Alert: An update to the Git Live is available. Run "git live update" to get the latest version.')."\n");
            // $this->ncecho(GitLive::VERSION.'->'.$this->getLatestVersion()."\n");
        }
    }

    /* ----------------------------------------- */


    /**
     * +-- 最終Versionを取得
     *
     * @access      public
     * @return string
     */
    public function getLatestVersion()
    {
        static $latest_version;

        if ($latest_version) {
            return $latest_version;
        }

        $latest_version_fetch_time = (int)$this->Driver('Config')->getParameter('latestversion.fetchtime');


        if (!empty($latest_version_fetch_time) && (time() - $latest_version_fetch_time) < $this->update_ck_span) {
            return $latest_version = $this->Driver('Config')->getParameter('latestversion.val');
        }

        $opts = array(
            'http' => array(
                'method' => 'GET',
                'header' => array(
                    'User-Agent: PHP',
                ),
            ),
        );

        $context  = stream_context_create($opts);
        $contents = file_get_contents(GitLive::VERSION_API, false, $context);
        if (!$contents) {
            $latest_version = GitLive::VERSION;
            return $latest_version;
        }

        $arr = json_decode($contents, true);
        if (substr($arr['tag_name'], 0, 1) === 'v') {
            $latest_version = substr($arr['tag_name'], 1);
        } else {
            $latest_version = $arr['tag_name'];
        }

        $this->Driver('Config')->setLocalParameter('latestversion.fetchtime', time());
        $this->Driver('Config')->setLocalParameter('latestversion.val', $latest_version);

        return $latest_version;
    }
    /* ----------------------------------------- */

    /**
     * +-- 新しいVersionが出ていないか確認する
     *
     * @access      public
     * @return bool
     */
    public function ckNewVersion()
    {
        $latest_version = $this->getLatestVersion();
        return (bool)version_compare(GitLive::VERSION, $latest_version, '<');
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @param  string                     $driver_name
     * @return \GitLive\Driver\DriverBase
     * @codeCoverageIgnore
     */
    public function Driver($driver_name)
    {
        if (!isset($this->Driver[$driver_name])) {
            $class_name                 = '\GitLive\Driver'.'\\'.$driver_name;
            $this->Driver[$driver_name] = new $class_name($this);
        }

        return $this->Driver[$driver_name];
    }
    /* ----------------------------------------- */

    /**
     * +-- \GitLive\GitCmdExecuter取得
     *
     * @access      public
     * @return \GitLive\GitCmdExecuter
     * @codeCoverageIgnore
     */
    public function getGitCmdExecuter()
    {
        return $this->GitCmdExecuter;
    }
    /* ----------------------------------------- */

    /**
     * +-- 処理の実行
     *
     * @access      public
     * @return void
     */
    public function execute()
    {
        $argv = $this->getArgv();

        if (!isset($argv[1])) {
            $this->Driver('Help')->help();

            return;
        }

        switch ($argv[1]) {
        case '--version':
        case '-v':
            $this->Driver('Help')->version();
        break;
        case 'start':
            $this->Driver('Init')->start();
        break;
        case 'merge':
            $this->Driver('Merge')->merge();
        break;
        case 'log':
            $this->Driver('Log')->log();
        break;
        case 'restart':
            $this->Driver('Init')->restart();
        break;
        case 'update':
            $this->Driver('Update')->update();
        break;
        case 'push':
            $this->push();
        break;
        case 'pull':
            $this->pull();
        break;
        case 'feature':
            $this->Driver('Feature')->feature();
        break;
        case 'pr':
            $this->Driver('PullRequest')->pr();
        break;
        case 'init':
            $this->Driver('Init')->init();
        break;
        case 'release':
            $this->Driver('Release')->release();
        break;
        case 'hotfix':
            $this->Driver('Hotfix')->hotfix();
        break;
        case 'clean':
            $this->clean();
        break;
        default:
            $this->Driver('Help')->help();
        break;
        }
    }

    /* ----------------------------------------- */

    /**
     * +-- リリースが空いているかどうか
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isReleaseOpen()
    {
        try {
            $this->getReleaseRepository();
        } catch (exception $e) {
            return false;
        }

        return true;
    }

    /* ----------------------------------------- */

    /**
     * +-- ホットフィクスが空いているかどうか
     *
     * @access      public
     * @return bool
     * @codeCoverageIgnore
     */
    public function isHotfixOpen()
    {
        try {
            $this->getHotfixRepository();
        } catch (exception $e) {
            return false;
        }

        return true;
    }

    /* ----------------------------------------- */

    /**
     * +-- releaseコマンド、hotfixコマンドが使用できるかどうか
     *
     * @access      public
     * @return void
     */
    public function enableRelease()
    {
        $remote = $this->GitCmdExecuter->remote();
        $remote = explode("\n", trim($remote));
        $res    = array_search($this->deploy_repository_name, $remote) !== false;
        if ($res === false) {
            throw new exception(
            sprintf(__('Add a remote repository %s.'), $this->deploy_repository_name)
            );
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- 使用しているリリースRepositoryの取得
     *
     * @access      public
     * @return string
     */
    public function getReleaseRepository()
    {
        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", trim($repository));

        $repo = false;
        foreach ($repository as $value) {
            if (mb_ereg('remotes/upstream/(release/[^/]*$)', $value, $match)) {
                $repo = $match[1];
                break;
            }
        }

        if (!$repo) {
            throw new exception('Release is not open.');
        }

        return trim($repo);
    }

    /* ----------------------------------------- */

    /**
     * +-- 使用しているhot fix Repositoryの取得
     *
     * @access      public
     * @return string
     */
    public function getHotfixRepository()
    {
        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", trim($repository));

        $repo = false;
        foreach ($repository as $value) {
            if (mb_ereg('remotes/upstream/(hotfix/[^/]*$)', $value, $match)) {
                $repo = $match[1];
                break;
            }
        }

        if (!$repo) {
            throw new exception('Hotfix is not open.');
        }

        return $repo;
    }

    /* ----------------------------------------- */

    /**
     * +-- 今のブランチを取得する
     *
     * @access      public
     * @return string
     */
    public function getSelfBranchRef()
    {
        $self_blanch = $this->exec('git symbolic-ref HEAD 2>/dev/null');
        if (!$self_blanch) {
            throw new exception(__('Not a git repository.'));
        }

        return trim($self_blanch);
    }

    /* ----------------------------------------- */


    /**
     * +-- 今のブランチを取得する
     *
     * @access      public
     * @return string
     */
    public function getSelfBranch()
    {
        $self_blanch = $this->exec('git rev-parse --abbrev-ref HEAD 2>/dev/null');
        if (!$self_blanch) {
            throw new exception(__('Not a git repository.'));
        }

        return trim($self_blanch);
    }

    /* ----------------------------------------- */


    public function isBranchExits($branch_name)
    {
        $branch_list_tmp = explode("\n", $this->GitCmdExecuter->branch());
        $branch_list     = array();
        foreach ($branch_list_tmp as $k => $branch_name_ck) {
            $branch_name_ck               = trim(mb_ereg_replace('^[*]', '', $branch_name_ck));
            $branch_name_ck               = trim(mb_ereg_replace('\s', '', $branch_name_ck));
            $branch_list[$branch_name_ck] = $branch_name_ck;
        }
        return isset($branch_list[$branch_name]);
    }

    /**
     * +-- コンフリクト確認
     *
     * @access      public
     * @param  string $from
     * @return bool
     */
    public function patchApplyCheck($from)
    {
        $cmd = 'git format-patch `git rev-parse --abbrev-ref HEAD`..'.$from.' --stdout| git apply --check';
        $res = $this->exec($cmd);
        $res = trim($res);
        return empty($res);
    }
    /* ----------------------------------------- */



    /**
     * +-- プッシュする
     *
     * @access      public
     * @return void
     */
    public function push()
    {
        $branch = $this->getSelfBranchRef();
        $remote = 'origin';

        if (strpos($branch, 'refs/heads/release') !== false || strpos($branch, 'refs/heads/hotfix') !== false) {
            $remote = 'upstream';
        }

        $this->GitCmdExecuter->push($remote, $branch);
    }

    /* ----------------------------------------- */

    /**
     * +-- プルする
     *
     * @access      public
     * @return void
     */
    public function pull()
    {
        $branch = $this->getSelfBranchRef();
        $remote = 'origin';

        switch ((string)$branch) {
        case 'refs/heads/develop':
        case 'refs/heads/master':
            $remote = 'upstream';
            break;
        default:
            if (strpos($branch, 'refs/heads/release') !== false || strpos($branch, 'refs/heads/hotfix') !== false) {
                $remote = 'upstream';
            }

        break;
        }

        $this->GitCmdExecuter->pull($remote, $branch);
    }

    /* ----------------------------------------- */

    /**
     * +-- ブランチの初期化
     *
     * @access      public
     * @return      void
     */
    public function clean()
    {
        $this->GitCmdExecuter->reset();
        $this->GitCmdExecuter->clean();
    }
    /* ----------------------------------------- */

    /**
     * +-- hotfixDestroyとreleaseDestroy共通処理
     *
     * @access      public
     * @param  var_text $repo
     * @param  var_text $mode
     * @param  var_text $remove_local OPTIONAL:false
     * @return void
     */
    public function deployDestroy($repo, $mode, $remove_local = false)
    {
        $argv = $this->getArgv();

        // Repositoryの掃除
        $this->GitCmdExecuter->push('deploy', ':'.$repo);
        $this->GitCmdExecuter->push('upstream', ':'.$repo);

        // ローカルブランチの削除
        if ($remove_local) {
            $this->clean();
            $this->GitCmdExecuter->branch(array('-d', $repo));
            $this->GitCmdExecuter->checkout('develop');
        }
    }

    /* ----------------------------------------- */



    /**
     * +-- hotfixCloseとreleaseClose共通処理
     *
     * @access      public
     * @param  var_text $repo
     * @param  var_text $mode
     * @param  var_text $force OPTIONAL:false
     * @return void
     */
    public function deployEnd($repo, $mode, $force = false)
    {
        $argv = $this->getArgv();

        // マスターのマージ
        $this->GitCmdExecuter->checkout('deploy/master');
        $this->GitCmdExecuter->branch(array('-D', 'master'));
        $this->GitCmdExecuter->checkout('master', array('-b'));

        if ($this->getSelfBranchRef() !== 'refs/heads/master') {
            $this->GitCmdExecuter->checkout($repo);
            $error_msg = sprintf(__('%1$s close is failed.'), $mode)."\n".
                sprintf(__('%1$s branch has a commit that is not on the %2$s branch'), 'Master', ucwords($mode));
            throw new exception($error_msg);
        }

        if (!$this->patchApplyCheck('deploy/'.$repo)) {
            $error_msg = sprintf(__('%1$s close is failed.'), $mode)."\n".
                sprintf(__('%1$s branch has a commit that is not on the %2$s branch'), 'Master', ucwords($mode));
            throw new exception($error_msg);
        }

        $this->GitCmdExecuter->merge('deploy/'.$repo);
        $diff = $this->GitCmdExecuter->diff(array('deploy/'.$repo, 'master'));

        if (strlen($diff) !== 0) {
            $error_msg = $diff."\n".sprintf(__('%1$s close is failed.'), $mode);
            throw new exception($error_msg);
        }

        $this->GitCmdExecuter->push('upstream', 'master');
        $this->GitCmdExecuter->push('deploy', 'master');

        // developのマージ
        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->branch(array('-D', 'develop'));
        $this->GitCmdExecuter->checkout('develop', array('-b'));

        if ($this->getSelfBranchRef() !== 'refs/heads/develop') {
            $this->GitCmdExecuter->checkout($repo);
            $error_msg = sprintf(__('%1$s close is failed.'), $mode)."\n".
                sprintf(__('%1$s branch has a commit that is not on the %2$s branch'), 'Develop', ucwords($mode));
            throw new exception($error_msg);
        }

        $this->GitCmdExecuter->merge('deploy/'.$repo);

        if ($mode === 'release' && !$force) {
            $diff = $this->GitCmdExecuter->diff(array('deploy/'.$repo, 'develop'));
        }

        if (strlen($diff) !== 0) {
            $error_msg = sprintf(__('%1$s close is failed.'), $mode)."\n".
                sprintf(__('%1$s branch has a commit that is not on the %2$s branch'), 'Develop', ucwords($mode));
            throw new exception($error_msg);
        }

        $this->GitCmdExecuter->push('upstream', 'develop');

        // Repositoryの掃除
        $this->GitCmdExecuter->push('deploy', ':'.$repo);
        $this->GitCmdExecuter->push('upstream', ':'.$repo);
        $this->GitCmdExecuter->branch(array('-d', $repo));

        // タグ付け
        $this->GitCmdExecuter->fetch(array('upstream'));
        $this->GitCmdExecuter->checkout('upstream/master');

        if (isset($argv[3])) {
            $tag = $argv[3];
        } else {
            list(, $tag) = explode('/', $repo);
            $tag         = 'r'.$tag;
        }

        $this->GitCmdExecuter->tag(array($tag));
        $this->GitCmdExecuter->tagPush('upstream');

        $this->GitCmdExecuter->checkout('develop');
    }

    /* ----------------------------------------- */

    /**
     * +-- DeployブランチをTrackする
     *
     * @access      public
     * @param  var_text $repo
     * @return void
     */
    public function deployTrack($repo)
    {
        if ($this->isBranchExits($repo)) {
            $this->GitCmdExecuter->checkout($repo, array());
        } else {
            $this->GitCmdExecuter->checkout('remote/'.$this->deploy_repository_name.'/'.$repo);
            $this->GitCmdExecuter->checkout($repo, array('-b'));
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- DeployブランチにSyncする
     *
     * @access      public
     * @param  var_text $repo
     * @return void
     */
    public function deploySync($repo)
    {
        $this->deployTrack($repo);

        $this->GitCmdExecuter->pull('deploy', $repo);
        $this->GitCmdExecuter->pull('upstream', $repo);

        $err = $this->GitCmdExecuter->status(array($repo));
        if (strpos(trim($err), 'nothing to commit') === false) {
            throw new exception($err);
        }

        $this->GitCmdExecuter->push('upstream', $repo);
        $this->GitCmdExecuter->push($this->deploy_repository_name, $repo);
    }

    /* ----------------------------------------- */

    /**
     * +-- upstream に pushする
     *
     * @access      public
     * @param  var_text $repo
     * @return void
     */
    public function deployPush($repo)
    {
        if (!$this->isBranchExits($repo)) {
            throw new exception('undefined '.$repo);
        }

        $this->GitCmdExecuter->checkout($repo);

        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->pull($this->deploy_repository_name, $repo);

        $err = $this->GitCmdExecuter->status(array($repo));
        if (strpos($err, 'nothing to commit') === false) {
            throw new exception($err);
        }

        $this->GitCmdExecuter->push('upstream', $repo);
    }

    /* ----------------------------------------- */
}

/* ----------------------------------------- */
