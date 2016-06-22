#!/usr/bin/php
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

ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);

$is_debug = true;

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
class GitLive extends GitBase
{
    protected $deploy_repository_name = 'deploy';
    protected $GitCmdExecuter;

    /**
     * +-- コンストラクタ
     *
     * @access      public
     * @return void
     */
    public function __construct()
    {
        $this->GitCmdExecuter = new GitCmdExecuter;
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
        global $argv;
        if (!isset($argv[1])) {
            $this->help();

            return;
        }
        switch ($argv[1]) {
        case 'start':
            $this->start();
        break;
        case 'merge':
            $this->merge();
        break;
        case 'log':
            $this->log();
        break;
        case 'restart':
            $this->restart();
        break;
        case 'update':
            $this->update();
        break;
        case 'push':
            $this->push();
        break;
        case 'pull':
            $this->pull();
        break;
        case 'feature':
            $this->feature();
        break;
        case 'pr':
            $this->pr();
        break;
        case 'init':
            $this->init();
        break;
        case 'release':
            $this->release();
        break;
        case 'hotfix':
            $this->hotfix();
        break;
        default:
            $this->help();
        break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- mergeを実行する
     *
     * @access      public
     * @return void
     */
    public function log()
    {
        global $argv;
        if (!isset($argv[2])) {
            $this->help();

            return;
        }
        switch ($argv[2]) {
            case 'develop':
                $this->logDevelop();
            break;
            case 'master':
                $this->logMaster();
            break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- mergeを実行する
     *
     * @access      public
     * @return void
     */
    public function merge()
    {
        global $argv;
        if (!isset($argv[2])) {
            $this->help();

            return;
        }
        switch ($argv[2]) {
            case 'develop':
                $this->mergeDevelop();
            break;
            case 'master':
                $this->mergeMaster();
            break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- プルリクエストの管理
     *
     * @access      public
     * @return void
     */
    public function pr()
    {
        global $argv;
        if (!isset($argv[2])) {
            $this->help();

            return;
        }

        switch ($argv[2]) {
        case 'track':
            if (!isset($argv[3])) {
                $this->help();

                return;
            }
            $this->prTrack($argv[3]);
        break;
        case 'pull':
            $this->prPull();
        break;
        case 'merge':
            if (!isset($argv[3])) {
                $this->help();

                return;
            }
            $this->prMerge($argv[3]);
        break;

        default:
            $this->help();
        break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- prTrack
     *
     * @access      public
     * @param  var_text $pull_request_number
     * @return void
     */
    public function prTrack($pull_request_number)
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p', 'deploy'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
        $this->GitCmdExecuter->fetchPullRequest();

        $repository = 'pullreq/'.$pull_request_number;
        $upstream_repository = 'remotes/pr/'.$pull_request_number.'/head';
        $this->GitCmdExecuter->checkout($upstream_repository, array('-b', $repository));
    }
    /* ----------------------------------------- */

    /**
     * +-- pr pull
     *
     * @access      public
     * @param  var_text $pull_request_number
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

    /**
     * +-- releaseを実行する
     *
     * @access      public
     * @return void
     */
    public function release()
    {
        global $argv;
        if (!isset($argv[2])) {
            $this->help();

            return;
        }
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p', 'deploy'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
        $this->enableRelease();
        switch ($argv[2]) {
        case 'open':
            $this->releaseOpen();
        break;
        case 'close':
            $this->releaseClose();
        break;
        case 'close-force':
            $this->releaseClose(true);
        break;
        case 'sync':
            $this->releaseSync();
        break;
        case 'state':
            $this->releaseState();
        break;
        case 'pull':
            $this->releasePull();
        break;
        case 'push':
            $this->releasePush();
        break;

        default:
            $this->help();
        break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- hotfixを実行する
     *
     * @access      public
     * @return void
     */
    public function hotfix()
    {
        global $argv;
        if (!isset($argv[2])) {
            $this->help();

            return;
        }
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p', 'deploy'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
        $this->enableRelease();
        switch ($argv[2]) {
        case 'open':
            $this->hotfixOpen();
        break;
        case 'close':
            $this->hotfixClose();
        break;
        case 'sync':
            $this->hotfixSync();
        break;
        case 'state':
            $this->hotfixState();
        break;
        case 'pull':
            $this->hotfixPull();
        break;
        case 'push':
            $this->hotfixPush();
        break;

        default:
            $this->help();
        break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- リリースが空いているかどうか
     *
     * @access      public
     * @return void
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
     * @return void
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
        $remote = explode("\n", $remote);
        $res =  array_search($this->deploy_repository_name, $remote) !== false;
        if ($res === false) {
            throw new exception('git live release を使用するには、'.$this->deploy_repository_name.' リポジトリを設定して下さい。');
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- 使用しているリリースRepositoryの取得
     *
     * @access      public
     * @return void
     */
    public function getReleaseRepository()
    {
        static $repo;
        if ($repo) {
            return $repo;
        }
        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", $repository);
        $repo = false;
        foreach ($repository as $value) {
            if (strpos($value, 'remotes/upstream/release/')) {
                mb_ereg('remotes/upstream/(release/[^/]*$)', $value, $match);
                $repo = $match[1];
                break;
            }
        }

        if (!$repo) {
            throw new exception ('リリースは開かれて居ません。');
        }

        return $repo;
    }
    /* ----------------------------------------- */

    /**
     * +-- 使用しているhot fix Repositoryの取得
     *
     * @access      public
     * @return void
     */
    public function getHotfixRepository()
    {
        static $repo;
        if ($repo) {
            return $repo;
        }
        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", $repository);
        $repo = false;
        foreach ($repository as $value) {
            if (strpos($value, 'remotes/upstream/hotfix/')) {
                mb_ereg('remotes/upstream/(hotfix/[^/]*$)', $value, $match);
                $repo = $match[1];
                break;
            }
        }

        if (!$repo) {
            throw new exception ('リリースは開かれて居ません。');
        }

        return $repo;
    }
    /* ----------------------------------------- */

    /**
     * +-- 初期化処理します
     *
     * @param  var_text $clone_repository
     * @param  var_text $upstream_repository
     * @param  var_text $deploy_repository
     * @param  var_text $clone_dir
     * @return void
     */
    public function init()
    {
        global $argv;
        if (!isset($argv[3])) {
            while (true) {
                $this->ncecho("Please enter your remote-repository.\n:");
                $clone_repository = trim(fgets(STDIN, 1000));
                if ($clone_repository == '') {
                    $this->ncecho(":");
                    continue;
                }
                if (!mb_ereg('/([^/]+?)(\.git)?$', $clone_repository, $match)) {
                    $this->ncecho(":");
                    continue;
                }
                break;

            }

            while (true) {
                $this->ncecho("Please enter common remote-repository.\n:");
                $upstream_repository = trim(fgets(STDIN, 1000));

                if ($upstream_repository == '') {
                    $this->ncecho(":");
                    continue;
                }
                break;
            }

            while (true) {
                $this->ncecho("Please enter deploying dedicated remote-repository.\n"
                ."If you return in the blank, it becomes the default setting.\n".
                "default:{$upstream_repository}\n:");
                $deploy_repository = trim(fgets(STDIN, 1000));

                if ($deploy_repository == '') {
                    $deploy_repository = $upstream_repository;
                }
                break;
            }

            while (true) {
                $this->ncecho("Please enter work directory path.\n".
                "If you return in the blank, it becomes the default setting.\n".
                "default:{$match[1]}\n:");
                $clone_dir = trim(fgets(STDIN, 1000));

                if ($clone_dir == '') {
                    $clone_dir = NULL;
                }
                break;
            }

        } else {
            $clone_repository    = $argv[2];

            $upstream_repository = $argv[3];
            if (isset($argv[5])) {
                $deploy_repository = $argv[4];
                $clone_dir         = $argv[5];
            } elseif (!isset($argv[4])) {
                $deploy_repository = NULL;
                $clone_dir         = NULL;
            } elseif (strpos($argv[4], 'git') === 0 || strpos($argv[4], 'https:') === 0 || is_dir(realpath($argv[4]).'/.git/')) {
                $deploy_repository = $argv[4];
                $clone_dir         = NULL;
            } else {
                $clone_dir         = $argv[4];
                $deploy_repository = NULL;
            }
        }

        if ($clone_dir === NULL) {
            if (!mb_ereg('/([^/]+?)(\.git)?$', $clone_repository, $match)) {
                $this->ncecho('fatal');

                return;
            }
            $clone_dir = getcwd().DIRECTORY_SEPARATOR.$match[1];
        }

        $this->GitCmdExecuter->copy(array('--recursive', $clone_repository, $clone_dir));

        chdir($clone_dir);
        $this->GitCmdExecuter->remote(array('add', 'upstream', $upstream_repository));

        if ($deploy_repository !== NULL) {
            $this->GitCmdExecuter->remote(array('add', 'deploy', $deploy_repository));
        }
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
            throw new exception('git repositoryではありません。');
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

        if (strpos($branch, 'refs/heads/release') || strpos($branch, 'refs/heads/hotfix')) {
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
            if (strpos($branch, 'refs/heads/release') || strpos($branch, 'refs/heads/hotfix')) {
                $remote = 'upstream';
            }
        break;
        }
        $this->GitCmdExecuter->pull($remote, $branch);
    }
    /* ----------------------------------------- */

    /**
     * +-- 諸々初期化します
     *
     * @access      public
     * @return void
     */
    public function start()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $this->GitCmdExecuter->pull('upstream', 'develop');
        $this->GitCmdExecuter->push('origin', 'develop');
        $this->GitCmdExecuter->pull('upstream', 'master');
        $this->GitCmdExecuter->push('origin', 'master');
    }
    /* ----------------------------------------- */

    /**
     * +-- 諸々リセットして初期化します
     *
     * @access      public
     * @return void
     */
    public function restart()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $this->GitCmdExecuter->checkout('temp', array('-b'));
        $this->GitCmdExecuter->branch(array('-d', 'develop'));
        $this->GitCmdExecuter->branch(array('-d', 'master'));
        $this->GitCmdExecuter->push('origin', ':develop');
        $this->GitCmdExecuter->push('origin', ':master');

        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout('develop', array('-b'));
        $this->GitCmdExecuter->push('origin', 'develop');

        $this->GitCmdExecuter->checkout('upstream/master');
        $this->GitCmdExecuter->checkout('master', array('-b'));
        $this->GitCmdExecuter->push('origin', 'master');
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
    }
    /* ----------------------------------------- */

    /**
     * +-- developをマージする
     *
     * @access      public
     * @return void
     */
    public function mergeDevelop()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $this->GitCmdExecuter->merge('upstream/develop');
    }
    /* ----------------------------------------- */

    /**
     * +-- masterをマージする
     *
     * @access      public
     * @return void
     */
    public function mergeMaster()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $this->GitCmdExecuter->merge('upstream/master');
    }
    /* ----------------------------------------- */

    /**
     * +-- developとの差分をみる
     *
     * @access      public
     * @return void
     */
    public function logDevelop()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $repository = $this->getSelfBranch();
        $this->ncecho($this->GitCmdExecuter->log('develop', $repository, '--left-right'));
    }
    /* ----------------------------------------- */

    /**
     * +-- masterとの差分を見る
     *
     * @access      public
     * @return void
     */
    public function logMaster()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $repository = $this->getSelfBranch();
        $this->ncecho($this->GitCmdExecuter->log('master', $repository, '--left-right'));
    }
    /* ----------------------------------------- */

    /**
     * +-- featureを実行する
     *
     * @access      public
     * @return void
     */
    public function feature()
    {
        global $argv;
        $this->GitCmdExecuter->fetch(array('upstream'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
        // $this->enableRelease();
        if (!isset($argv[2])) {
            $this->help();

            return;
        }
        switch ($argv[2]) {
        case 'start':
            if (!isset($argv[3])) {
                $this->help();

                return;
            }
            $this->featureStart($argv[3]);
        break;
        case 'publish':
            $this->featurePublish(isset($argv[3]) ? $argv[3] : NULL);
        break;
        case 'push':
            $this->featurePush(isset($argv[3]) ? $argv[3] : NULL);
        break;
        case 'close':
            $this->featureClose(isset($argv[3]) ? $argv[3] : NULL);
        break;
        case 'track':
            if (!isset($argv[3])) {
                $this->help();

                return;
            }
            $this->featureTrack($argv[3]);
        break;
        case 'pull':
            $this->featurePull(isset($argv[3]) ? $argv[3] : NULL);
        break;

        default:
            $this->help();
        break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- featureを開始する
     *
     * @access      public
     * @param  var_text $repository
     * @return void
     */
    public function featureStart($repository)
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        if (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }
        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout($repository, array('-b'));
    }
    /* ----------------------------------------- */

    /**
     * +-- 共用Repositoryにfeatureを送信する
     *
     * @access      public
     * @param  var_text $repository OPTIONAL:NULL
     * @return void
     */
    public function featurePublish($repository = NULL)
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        if ($repository === NULL) {
            $repository = $this->getSelfBranch();
        } elseif (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }
        $this->GitCmdExecuter->push('upstream', $repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- 自分のリモートRepositoryにfeatureを送信する
     *
     * @access      public
     * @param  var_text $repository OPTIONAL:NULL
     * @return void
     */
    public function featurePush($repository = NULL)
    {
        if ($repository === NULL) {
            $repository = $this->getSelfBranch();
        } elseif (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }
        $this->GitCmdExecuter->push('origin', $repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- 共用Repositoryから他人のfeatureを取得する
     *
     * @access      public
     * @param  var_text $repository
     * @return void
     */
    public function featureTrack($repository)
    {
        if (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }
        $self_repository = $this->getSelfBranch();
        $this->GitCmdExecuter->pull('upstream', $repository);

        if ($self_repository !== $repository) {
            $this->GitCmdExecuter->checkout($repository);
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- 共用Repositoryからpullする
     *
     * @access      public
     * @param  var_text $repository OPTIONAL:NULL
     * @return void
     */
    public function featurePull($repository = NULL)
    {
        if ($repository === NULL) {
            $repository = $this->getSelfBranch();
        } elseif (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }
        $this->GitCmdExecuter->pull('upstream', $repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- featureを閉じる
     *
     * @access      public
     * @param  var_text $repository OPTIONAL:NULL
     * @return void
     */
    public function featureClose($repository = NULL)
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        if ($repository === NULL) {
            $repository = $this->getSelfBranch();
        } elseif (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }
        $this->GitCmdExecuter->push('upstream', ':'.$repository);
        $this->GitCmdExecuter->push('origin', ':'.$repository);
        $this->GitCmdExecuter->checkout('develop');
        $this->GitCmdExecuter->branch(array('-D', $repository));
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
        global $argv;

        // マスターのマージ
        $this->GitCmdExecuter->checkout('deploy/master');
        $this->GitCmdExecuter->branch(array('-D', 'master'));
        $this->GitCmdExecuter->checkout('master', array('-b'));

        if ($this->getSelfBranch() !== 'refs/heads/master') {
            $this->GitCmdExecuter->checkout($repo);
            throw new exception ($mode.' closeに失敗しました。'."\n".' masterがReleaseブランチより進んでいます。'."\n".'[http://www.enviphp.net/c/man/v3/gitlive/error/500]'.__LINE__);
        }

        $this->GitCmdExecuter->merge('deploy/'.$repo);
        $diff = $this->GitCmdExecuter->diff(array('deploy/'.$repo ,'master'));

        if (strlen($diff) !== 0) {
            throw new exception($diff."\n{$mode} ".'closeに失敗しました。'.__LINE__);
        }
        $this->GitCmdExecuter->push('upstream', 'master');
        $this->GitCmdExecuter->push('deploy', 'master');

        // developのマージ
        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->branch(array('-D', 'develop'));
        $this->GitCmdExecuter->checkout('develop', array('-b'));

        if ($this->getSelfBranch() !== 'refs/heads/develop') {
            $this->GitCmdExecuter->checkout($repo);
            throw new exception ($mode.' closeに失敗しました。'.__LINE__);
        }

        $this->GitCmdExecuter->merge('deploy/'.$repo);

        if ($mode === 'release' && !$force) {
            $diff = $this->GitCmdExecuter->diff(array('deploy/'.$repo ,'develop'));
        }

        if (strlen($diff) !== 0) {
            throw new exception ($mode.' closeに失敗しました。'."\n".' developがReleaseブランチより進んでいます。'."\n".'[http://www.enviphp.net/c/man/v3/gitlive/error/501]'.__LINE__);
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
            $tag = 'r'.$tag;
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
        $err =  $this->GitCmdExecuter->status(array($repo));
        if (strpos(trim($err), 'nothing to commit') === false) {
            throw new exception ($err);
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
        $err =  $this->GitCmdExecuter->status(array($repo));
        if (strpos($err, 'nothing to commit') === false) {
            throw new exception ($err);
        }
        $this->GitCmdExecuter->push('upstream', $repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- hotfixを開く
     *
     * @access      public
     * @return void
     */
    public function hotfixOpen()
    {
        if ($this->isReleaseOpen()) {
            throw new exception('リリースが既に空いてます。');
        } elseif ($this->isHotfixOpen()) {
            throw new exception('hotfixが既に空いてます。');
        }

        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", $repository);
        foreach ($repository as $value) {
            if (strpos($value, 'remotes/'.$this->deploy_repository_name.'/hotfix/')) {
                throw new exception('既にhotfix open されています。'.$value);
            }
        }
        $hotfix_rep = 'hotfix/'.date('Ymdhis');

        $this->GitCmdExecuter->checkout('upstream/master');
        $this->GitCmdExecuter->checkout($hotfix_rep, array('-b'));

        $this->GitCmdExecuter->push('upstream', $hotfix_rep);
        $this->GitCmdExecuter->push('deploy', $hotfix_rep);
    }
    /* ----------------------------------------- */

    /**
     * +-- 誰かが開けたhotfixをトラックする
     *
     * @access      public
     * @return void
     */
    public function hotfixTrack()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception('hotfixが空いていません。');
        }
        $repo = $this->getHotfixRepository();
        $this->GitCmdExecuter->pull('deploy', $repo);
        $this->GitCmdExecuter->checkout($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- 誰かが開けたhotfixをトラックする
     *
     * @access      public
     * @return void
     */
    public function hotfixPull()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception('hotfixが空いていません。');
        }
        $repo = $this->getHotfixRepository();
        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->checkout($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- hotfixの状態を確かめる
     *
     * @access      public
     * @return void
     */
    public function hotfixState()
    {
        if ($this->isHotfixOpen()) {
            $repo = $this->getHotfixRepository();
            $this->ncecho($this->GitCmdExecuter->log('master', $repo));
            $this->ncecho("hotfix is open.\n");

            return;
        }
        $this->ncecho("hotfix is close.\n");
    }
    /* ----------------------------------------- */

    /**
     * +-- コードを各環境に反映する
     *
     * @access      public
     * @return void
     */
    public function hotfixSync()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception('hotfixが空いていません。');
        }

        $repo = $this->getHotfixRepository();

        $this->deploySync($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- コードを各環境に反映する
     *
     * @access      public
     * @return void
     */
    public function hotfixPush()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception('hotfixが空いていません。');
        }

        $repo = $this->getHotfixRepository();

        $this->deployPush($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- hotfixを閉じる
     *
     * @access      public
     * @return void
     */
    public function hotfixClose()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception('hotfixが空いていません。');
        }

        $repo = $this->getHotfixRepository();
        $this->deployEnd($repo, 'hotfix');
    }
    /* ----------------------------------------- */

    /**
     * +-- リリースを開く
     *
     * @access      public
     * @return void
     */
    public function releaseOpen()
    {
        if ($this->isReleaseOpen()) {
            throw new exception('リリースが既に空いてます。');
        } elseif ($this->isHotfixOpen()) {
            throw new exception('hotfixが既に空いてます。');
        }

        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", $repository);
        foreach ($repository as $value) {
            if (strpos($value, 'remotes/'.$this->deploy_repository_name.'/release/')) {
                throw new exception('既にrelease open されています。'.$value);
            }
        }
        $release_rep = 'release/'.date('Ymdhis');

        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout($release_rep, array('-b'));

        $this->GitCmdExecuter->push('upstream', $release_rep);
        $this->GitCmdExecuter->push('deploy', $release_rep);
    }
    /* ----------------------------------------- */

    /**
     * +-- 誰かが開けたリリースをトラックする
     *
     * @access      public
     * @return void
     */
    public function releaseTrack()
    {
        if (!$this->isReleaseOpen()) {
            throw new exception('リリースが空いていません。');
        }
        $repo = $this->getReleaseRepository();
        $this->GitCmdExecuter->pull('deploy', $repo);
        $this->GitCmdExecuter->checkout($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- 誰かが開けたリリースをpullする
     *
     * @access      public
     * @return void
     */
    public function releasePull()
    {
        if (!$this->isReleaseOpen()) {
            throw new exception('リリースが空いていません。');
        }
        $repo = $this->getReleaseRepository();
        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->checkout($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- リリースの状態を確かめる
     *
     * @access      public
     * @return void
     */
    public function releaseState()
    {
        if ($this->isReleaseOpen()) {
            $repo = $this->getReleaseRepository();
            $this->ncecho($this->GitCmdExecuter->log('master', $repo));
            $this->ncecho("release is open.\n");

            return;
        }
        $this->ncecho("release is close.\n");
    }
    /* ----------------------------------------- */

    /**
     * +-- コードを各環境に反映する
     *
     * @access      public
     * @return void
     */
    public function releaseSync()
    {
        if (!$this->isReleaseOpen()) {
            throw new exception('リリースが空いていません。');
        }

        $repo = $this->getReleaseRepository();
        $this->deploySync($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- コードを各環境に反映する
     *
     * @access      public
     * @return void
     */
    public function releasePush()
    {
        if (!$this->isReleaseOpen()) {
            throw new exception('リリースが空いていません。');
        }

        $repo = $this->getReleaseRepository();
        $this->deployPush($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- リリースを閉じる
     *
     * @access      public
     * @param  boolean $force OPTIONAL:false
     * @return void
     */
    public function releaseClose($force = false)
    {
        global $argv;
        if (!$this->isReleaseOpen()) {
            throw new exception('リリースが空いていません。');
        }

        $repo = $this->getReleaseRepository();
        $this->deployEnd($repo, 'release', $force);
    }
    /* ----------------------------------------- */
}
/* ----------------------------------------- */

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
/* ----------------------------------------- */

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
class GitBase
{
    public function debug($text, $color = NULL)
    {
        global $is_debug;
        if (!$is_debug) {
            return;
        }
        if ($color === NULL) {
            $this->ncecho($text);

            return;
        }
        $this->cecho($text, $color);
    }

    /**
     * +-- 色つきecho
     *
     * @access      public
     * @param  var_text $text
     * @param  var_text $color
     * @return void
     */
    public function cecho($text, $color)
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            $this->ncecho($text);

            return;
        }
        $cmd = 'echo -e "\e[3'.$color.'m'.escapeshellarg($text).'\e[m"';
        `$cmd`;
    }
    /* ----------------------------------------- */

    /**
     * +-- 色なしecho
     *
     * @access      public
     * @param  var_text $text
     * @return void
     */
    public function ncecho($text)
    {
        $text = _($text);
        if (DIRECTORY_SEPARATOR === '\\') {
            $text = mb_convert_encoding($text, 'SJIS-win', 'utf8');
        }
        echo $text;
    }
    /* ----------------------------------------- */

    /**
     * +-- コマンドのアップデート
     *
     * @access      public
     * @return void
     */
    public function update()
    {
        $url = 'https://raw.githubusercontent.com/Git-Live/git-live/master/git-live.php';
        file_put_contents(__FILE__, file_get_contents($url));
    }
    /* ----------------------------------------- */

    /**
     * +-- ヘルプの表示
     *
     * @access      public
     * @return void
     */
    public function help()
    {
        $this->ncecho("GIT-LIVE(1)                      Git Manual                      GIT-LIVE(1)\n");
        $this->ncecho("NAME\n");
        $this->ncecho("       git-live - 安全で効率的な、リポジトリ運用をサポートします。\n");
        $this->ncecho("SYNOPSIS\n");
        $this->ncecho("       git live feature start <feature name>\n");
        $this->ncecho("       git live feature publish\n");
        $this->ncecho("       git live feature track\n");
        $this->ncecho("       git live feature push\n");
        $this->ncecho("       git live feature pull\n");
        $this->ncecho("       git live feature close\n");

        $this->ncecho("       git live pr track\n");
        $this->ncecho("       git live pr pull\n");
        $this->ncecho("       git live pr merge\n");

        $this->ncecho("       git live hotfix open <release name>\n");
        $this->ncecho("       git live hotfix close\n");
        $this->ncecho("       git live hotfix sync\n");
        $this->ncecho("       git live hotfix state\n");
        $this->ncecho("       git live hotfix track\n");
        $this->ncecho("       git live hotfix pull\n");
        $this->ncecho("       git live hotfix push\n");

        $this->ncecho("       git live release open <release name>\n");
        $this->ncecho("       git live release close\n");
        $this->ncecho("       git live release sync\n");
        $this->ncecho("       git live release state\n");
        $this->ncecho("       git live release track\n");
        $this->ncecho("       git live release pull\n");
        $this->ncecho("       git live release push\n");

        $this->ncecho("       git live pull\n");
        $this->ncecho("       git live push\n");
        $this->ncecho("       git live update\n");

        $this->ncecho("       git live merge develop\n");
        $this->ncecho("       git live merge master\n");

        $this->ncecho("       git live log develop\n");
        $this->ncecho("       git live log master\n");

        $this->ncecho("       git live init\n");
        $this->ncecho("       git live start\n");
        $this->ncecho("       git live restart\n");

        $this->ncecho("OPTIONS\n");
        $this->ncecho("       feature start <feature name>\n");
        $this->ncecho("           新しい開発用ブランチを作成して、開発を始めます。\n");
        $this->ncecho("       feature publish\n");
        $this->ncecho("           upstreamに開発用のブランチをpushします。\n");
        $this->ncecho("       feature track <feature name>\n");
        $this->ncecho("           upstreamから開発用ブランチを取得します。\n");
        $this->ncecho("       feature push\n");
        $this->ncecho("           originに開発ブランチをpushします。(git live pushと動作は似ています)\n");
        $this->ncecho("       feature pull\n");
        $this->ncecho("           originから開発ブランチをpullします。(git live pullと動作は似ています)\n");
        $this->ncecho("       feature close\n");
        $this->ncecho("           すべての場所から、開発ブランチを削除します。プルリクエストがマージされたあとに実行してください。\n");

        $this->ncecho("       pr track <pull request number>\n");
        $this->ncecho("           upstreamからpull requestされているコードを取得します。\n");
        $this->ncecho("       pr pull \n");
        $this->ncecho("           pull requestの内容を最新化\n");
        $this->ncecho("       pr merge <pull request number>\n");
        $this->ncecho("           pull requestの内容をマージする。\n");

        $this->ncecho("       hotfix open <release name>\n");
        $this->ncecho("           hotfix用のブランチを作成します。\n");
        $this->ncecho("       hotfix close\n");
        $this->ncecho("           hotfixを終了し、マスターとdevelopにコードをマージします。\n");
        $this->ncecho("       hotfix sync\n");
        $this->ncecho("           リリースに、upstream内のhotfixブランチをマージします。\n");
        $this->ncecho("       hotfix state\n");
        $this->ncecho("           hotfixの状態を確認します。\n");
        $this->ncecho("       hotfix track\n");
        $this->ncecho("           誰かが開けたhotfixを取得します。\n");
        $this->ncecho("       hotfix pull\n");
        $this->ncecho("           デプロイブランチとupstreamからpullします。\n");
        $this->ncecho("       hotfix push\n");
        $this->ncecho("           デプロイブランチとupstreamにpushします。\n");

        $this->ncecho("       release open <release name>\n");
        $this->ncecho("           release用のブランチを作成します。\n");
        $this->ncecho("       release close\n");
        $this->ncecho("           releaseを終了し、マスターとdevelopにコードをマージします。\n");
        $this->ncecho("       release sync\n");
        $this->ncecho("           リリースに、upstream内のreleaseブランチをマージします。\n");
        $this->ncecho("       release state\n");
        $this->ncecho("           releaseの状態を確認します。\n");
        $this->ncecho("       release pull\n");
        $this->ncecho("           デプロイブランチとupstreamからpullします。\n");
        $this->ncecho("       release push\n");
        $this->ncecho("           デプロイブランチとupstreamにpushします。\n");

        $this->ncecho("       pull\n");
        $this->ncecho("           適当な場所から、pullします。\n");
        $this->ncecho("       push\n");
        $this->ncecho("           適当な場所に、pushします。\n");
        $this->ncecho("       update\n");
        $this->ncecho("           コマンドラインの最新化。\n");
        $this->ncecho("       merge develop\n");
        $this->ncecho("           developからmerge\n");
        $this->ncecho("       merge master\n");
        $this->ncecho("           masterからmerge\n");

        $this->ncecho("       log develop\n");
        $this->ncecho("           developとのdiff\n");
        $this->ncecho("       log master\n");
        $this->ncecho("           masterとのdiff\n");

        $this->ncecho("       start\n");
        $this->ncecho("           初期化します。\n");
        $this->ncecho("       restart\n");
        $this->ncecho("           リポジトリを再構築します。\n");

        $this->ncecho("       init <clone_repository> <upstream_repository> <deploy_repository> (<clone_dir>)\n");
        $this->ncecho("           gitlive で管理するリポジトリを作成する\n");
        $this->ncecho("           clone_repository：\n");
        $this->ncecho("               cloneする個人開発用のリモートリポジトリ(origin)\n");
        $this->ncecho("           upstream_repository：\n");
        $this->ncecho("               originのfork元リモートリポジトリ(upstream)\n");
        $this->ncecho("           deploy_repository：\n");
        $this->ncecho("               デプロイ先リポジトリ\n");
        $this->ncecho("           clone_dir：\n");
        $this->ncecho("               cloneするディレクトリ\n");
    }
    /* ----------------------------------------- */

}


try {
    if (DIRECTORY_SEPARATOR === '\\') {
        mb_internal_encoding('utf8');
        mb_http_output('sjis-win');
        mb_http_input('sjis-win');
    }
    $GitLive = new GitLive;
    $GitLive->execute();
} catch (exception $e) {
    $this->ncecho($e->getMessage()."\n");
}
