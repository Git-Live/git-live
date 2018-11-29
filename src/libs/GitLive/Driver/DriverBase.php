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
use GitLive\GitCmdExecutor;
use GitLive\GitLive;
use GitLive\Support\SystemCommandInterface;

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
abstract class DriverBase
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
     * @return string
     */
    public function exec($cmd, $verbosity = true)
    {
        return $this->command->exec($cmd, $verbosity);
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
     * @param $branch_name
     * @return bool
     */
    public function isBranchExits($branch_name)
    {
        $branch_list_tmp = explode("\n", $this->GitCmdExecutor->branch());
        $branch_list = [];
        foreach ($branch_list_tmp as $k => $branch_name_ck) {
            $branch_name_ck = trim(mb_ereg_replace('^[*]', '', $branch_name_ck));
            $branch_name_ck = trim(mb_ereg_replace('\s', '', $branch_name_ck));
            $branch_list[$branch_name_ck] = $branch_name_ck;
        }

        return isset($branch_list[$branch_name]);
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
        $cmd = 'git format-patch `git rev-parse --abbrev-ref HEAD`..' . $from . ' --stdout| git apply --check';
        $res = $this->exec($cmd);
        $res = trim($res);

        return empty($res);
    }

    /**
     * コンフリクト確認結果の取得
     *
     * @param string $from
     * @return string
     */
    public function patchApplyDiff($from)
    {
        $cmd = 'git format-patch `git rev-parse --abbrev-ref HEAD`..' . $from . ' --stdout| git apply --check';
        $res = $this->exec($cmd);

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
        $res = trim($this->exec('git rev-parse --git-dir 2> /dev/null'));

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
}
