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
     * +-- 引数配列を返す
     *
     * @access      public
     * @return array
     * @codeCoverageIgnore
     */
    public function getArgv()
    {
        global $argv;
        return $argv;
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
            sprintf(_('git live release を使用するには、%s リポジトリを設定して下さい。'), $this->deploy_repository_name)
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
        $repo       = false;
        foreach ($repository as $value) {
            if (mb_ereg('remotes/upstream/(release/[^/]*$)', $value, $match)) {
                $repo = $match[1];
                break;
            }
        }

        if (!$repo) {
            throw new exception('release openされていません。');
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

        $repo       = false;
        foreach ($repository as $value) {
            if (mb_ereg('remotes/upstream/(hotfix/[^/]*$)', $value, $match)) {
                $repo = $match[1];
                break;
            }
        }

        if (!$repo) {
            throw new exception('hotfix openされていません。');
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
    public function getSelfBranch()
    {
        $self_blanch = `git symbolic-ref HEAD 2>/dev/null`;
        if (!$self_blanch) {
            throw new exception(_('git repositoryではありません。'));
        }

        return trim($self_blanch);
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
        $branch = $this->getSelfBranch();
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
        $branch = $this->getSelfBranch();
        $remote = 'origin';
        switch ($branch) {
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

        if ($this->getSelfBranch() !== 'refs/heads/master') {
            $this->GitCmdExecuter->checkout($repo);
            throw new exception($mode.' '._('closeに失敗しました。')."\n"._('masterがReleaseブランチより進んでいます。'));
        }

        $this->GitCmdExecuter->merge('deploy/'.$repo);
        $diff = $this->GitCmdExecuter->diff(array('deploy/'.$repo, 'master'));

        if (strlen($diff) !== 0) {
            throw new exception($diff."\n".$mode.' '._('closeに失敗しました。'));
        }

        $this->GitCmdExecuter->push('upstream', 'master');
        $this->GitCmdExecuter->push('deploy', 'master');

        // developのマージ
        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->branch(array('-D', 'develop'));
        $this->GitCmdExecuter->checkout('develop', array('-b'));

        if ($this->getSelfBranch() !== 'refs/heads/develop') {
            $this->GitCmdExecuter->checkout($repo);
            throw new exception($mode.' '._('closeに失敗しました。')."\n"._('developがReleaseブランチより進んでいます。'));
        }

        $this->GitCmdExecuter->merge('deploy/'.$repo);

        if ($mode === 'release' && !$force) {
            $diff = $this->GitCmdExecuter->diff(array('deploy/'.$repo, 'develop'));
        }

        if (strlen($diff) !== 0) {
            throw new exception($mode.' '._('closeに失敗しました。')."\n"._('developがReleaseブランチより進んでいます。'));
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
        $this->GitCmdExecuter->checkout($repo, array('-b'));
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
        $this->GitCmdExecuter->checkout($repo);
        $this->GitCmdExecuter->pull('upstream', $repo);
        $err = $this->GitCmdExecuter->status(array($repo));
        if (strpos($err, 'nothing to commit') === false) {
            throw new exception($err);
        }

        $this->GitCmdExecuter->push('upstream', $repo);
    }

    /* ----------------------------------------- */
}

/* ----------------------------------------- */
