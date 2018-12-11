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
use GitLive\GitBase;
use GitLive\GitCmdExecutor;
use GitLive\GitLive;
use GitLive\Support\FileSystem;
use GitLive\Support\SystemCommandInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DriverBase
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
abstract class DriverBase extends GitBase
{
    /**
     * @var GitLive
     */
    protected $GitLive;

    /**
     * @var \GitLive\GitCmdExecutor
     */
    protected $GitCmdExecutor;

    /**
     * @var SystemCommandInterface
     */
    protected $command;

    /**
     * コンストラクタ
     *
     * @access      public
     * @param  GitLive               $GitLive
     * @param GitCmdExecutor         $gitCmdExecutor
     * @param SystemCommandInterface $command
     * @codeCoverageIgnore
     */
    public function __construct($GitLive, GitCmdExecutor $gitCmdExecutor, SystemCommandInterface $command)
    {
        $this->GitLive = $GitLive;
        $this->GitCmdExecutor = $gitCmdExecutor;
        $this->command = $command;
    }

    /**
     * 今のブランチを取得する
     *
     * @access      public
     * @throws Exception
     * @throws Exception
     * @return string
     */
    public function getSelfBranchRef()
    {
        $self_blanch = $this->exec('git symbolic-ref HEAD 2>/dev/null', true);

        if (!$self_blanch) {
            throw new Exception(__('Not a git repository.'));
        }

        return trim($self_blanch);
    }

    /**
     * Commandの実行
     *
     * 単体テストを楽にするために、処理を上書きして委譲する
     *
     * @access      public
     * @param  string $cmd
     * @param bool    $verbosity
     * @param null    $output_verbosity
     * @return string
     */
    public function exec($cmd, $verbosity = true, $output_verbosity = null)
    {
        return $this->command->exec($cmd, $verbosity, $output_verbosity);
    }

    /**
     * 今のブランチを取得する
     *
     * @access      public
     * @throws Exception
     * @throws Exception
     * @return string
     */
    public function getSelfBranch()
    {
        $self_blanch = $this->exec('git rev-parse --abbrev-ref HEAD 2>/dev/null');
        if (!$self_blanch) {
            throw new Exception(__('Not a git repository.'));
        }

        return trim($self_blanch);
    }

    /**
     *
     *
     * @access      public
     * @param  string $driver_name
     * @throws Exception
     * @return \GitLive\Driver\DriverBase
     * @codeCoverageIgnore
     */
    public function Driver($driver_name)
    {
        try {
            return App::make($driver_name);
        } catch (\ReflectionException $exception) {
            dd($exception);
        }

        throw new Exception('Undefined Driver.' . $driver_name);
    }

    /**
     * @param string $branch_name
     * @throws Exception
     * @return bool
     */
    public function isBranchExists($branch_name)
    {
        return $this->Driver(BranchDriver::class)->isBranchExistsSimple($branch_name);
    }

    /**
     * @param null|string $repo
     * @return bool
     */
    public function isClean($repo = null)
    {
        if ($repo === null) {
            $err = $this->GitCmdExecutor->status([], true);
        } else {
            $err = $this->GitCmdExecutor->status([$repo], true);
        }

        if (strpos(trim($err), 'nothing to commit') === false) {
            return false;
        }

        return true;
    }

    /**
     * @param null|string $repo
     * @param null|string $error_msg
     * @throws Exception
     * @return bool
     */
    public function isCleanOrFail($repo = null, $error_msg = null)
    {
        if ($repo === null) {
            $err = $this->GitCmdExecutor->status([], true);
        } else {
            $err = $this->GitCmdExecutor->status([$repo], true);
        }

        if (strpos(trim($err), 'nothing to commit') === false) {
            throw new Exception(($error_msg ?? __('Please clean or commit.')) . "\n" . $err);
        }

        return true;
    }

    /**
     * Riskyな状態かどうか
     *
     * @throws \ReflectionException
     * @return bool
     */
    public function isRisky()
    {
        $remotes = $this->GitCmdExecutor->remote(['-v'], OutputInterface::VERBOSITY_DEBUG);
        $origin_push = null;
        if (!mb_ereg('origin\\s+([^ ]+)\\s+\\(push\\)', $remotes, $origin_push)) {
            return true;
        }
        $origin_push = $origin_push[1];

        $deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();
        $deploy_push = null;
        if (!mb_ereg($deploy_repository_name . '\\s+([^ ]+)\\s+\\(push\\)', $remotes, $deploy_push)) {
            return true;
        }

        $deploy_push = $deploy_push[1];

        $upstream_push = null;
        if (!mb_ereg('upstream\\s+([^ ]+)\\s+\\(push\\)', $remotes, $upstream_push)) {
            return true;
        }

        $upstream_push = $upstream_push[1];
        
        if ($origin_push === $deploy_push) {
            return true;
        }

        if ($origin_push === $upstream_push) {
            return true;
        }

        return false;
    }

    /**
     * コンフリクト確認
     *
     * @access      public
     * @param  string $from
     * @return bool
     */
    public function patchApplyCheck($from)
    {
        $res = $this->patchApplyDiff($from, OutputInterface::VERBOSITY_DEBUG);

        return empty($res);
    }

    /**
     * コンフリクト確認結果の取得
     *
     * @param string $from
     * @param bool   $verbosity
     * @return string
     */
    public function patchApplyDiff($from, $verbosity = false)
    {
        // 一度diffを取る
        $cmd = 'git format-patch `git rev-parse --abbrev-ref HEAD`..' . $from . ' --stdout';
        $ck = trim($this->exec($cmd, OutputInterface::VERBOSITY_DEBUG, OutputInterface::VERBOSITY_DEBUG));

        if (strlen($ck) === 0) {
            return '';
        }

        $cmd = 'git format-patch `git rev-parse --abbrev-ref HEAD`..' . $from . ' --stdout| git apply --check';
        $res = $this->exec($cmd, $verbosity, OutputInterface::VERBOSITY_DEBUG);

        return trim($res);
    }

    /**
     * @param string $dir
     */
    public function chdir($dir)
    {
        $this->GitCmdExecutor->chdir($dir);
    }

    /**
     * gitRepository上かどうか
     *
     * @access      public
     * @return      bool
     */
    public function isGitRepository()
    {
        $res = trim($this->exec('git rev-parse --git-dir 2> /dev/null', OutputInterface::VERBOSITY_DEBUG, OutputInterface::VERBOSITY_DEBUG));

        return !empty($res);
    }

    /**
     *
     */
    public function clean()
    {
        $this->GitCmdExecutor->reset();
        $this->GitCmdExecutor->clean();
    }

    /**
     * @throws \ReflectionException
     * @return \GitLive\Support\Collection
     */
    public function getGitLiveSetting()
    {
        $setting = collect([]);

        $dir = $this->GitCmdExecutor->topLevelDir() . DIRECTORY_SEPARATOR;
        if (is_file($dir . '.gitlive.dist.json')) {
            $setting = $setting->merge(collect(json_decode(App::make(FileSystem::class)->getContents($dir . '.gitlive.dist.json'))));
        }
        if (is_file($dir . '.gitlive.json')) {
            $setting = $setting->merge(collect(json_decode(App::make(FileSystem::class)->getContents($dir . '.gitlive.json'))));
        }

        return $setting;
    }
}
