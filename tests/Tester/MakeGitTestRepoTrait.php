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

namespace Tests\GitLive\Tester;

use GitLive\Support\Collection;

/**
 * Class MakeGitTestRepoTrait
 *
 * @category   GitCommand
 * @package    Tests\GitLive\Tester
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-16
 * @codeCoverageIgnore
 */
trait MakeGitTestRepoTrait
{
    protected $remote_origin_repository;
    protected $remote_upstream_repository;
    protected $remote_deploy_repository;
    protected $local_test_repository;

    public function assertHasBranch($branch_name)
    {
        $branch_list = $this->makeArray($this->execCmdToLocalRepo('git branch -a'));

        $this->assertTrue($branch_list->search($branch_name) !== false);
    }

    public function execCmdToLocalRepo($cmd)
    {
        // カレント取得
        $current_work_dir = getcwd();

        chdir($this->local_test_repository);

        $execute_cmd = $cmd . ' 2>&1';
        $res = shell_exec($execute_cmd);

        // 場所をもとに戻す
        chdir($current_work_dir);

        return $res;
    }

    public function assertHasNotBranch($branch_name)
    {
        $branch_list = $this->makeArray($this->execCmdToLocalRepo('git branch -a'));

        $this->assertFalse($branch_list->search($branch_name) !== false);
    }

    protected function makeGitTestRepoTraitBoot()
    {
        // カレント取得
        $current_work_dir = getcwd();

        $ds = DIRECTORY_SEPARATOR;
        $storage = PROJECT_ROOT_DIR . $ds . 'storage' . $ds . 'unit_testing';

        // 初期化
        $cmd = "rm -rf {$storage}";
        shell_exec($cmd);

        $remote_origin = $storage . $ds . 'git_live_origin_test.git';
        $remote_upstream = $storage . $ds . 'git_live_upstream_test.git';
        $remote_deploy = $storage . $ds . 'git_live_deploy_test.git';
        $init_working = $storage . $ds . 'init_working';
        $local_test = $storage . $ds . 'local_test';

        shell_exec("git init --bare --shared=true {$remote_upstream}");

        mkdir($init_working);
        chdir($init_working);
        shell_exec('git init');
        shell_exec("git remote add origin {$remote_upstream}");
        shell_exec('git remote -v');
        file_put_contents($init_working . $ds . 'README.md', '# unit testing Read me');
        file_put_contents($init_working . $ds . 'LICENSE.md', '# unit testing License');
        shell_exec('git add ./');
        shell_exec('git commit -am "init"');
        shell_exec('git push origin master');
        shell_exec('git checkout -b develop');
        shell_exec('git push origin develop');

        shell_exec("git clone {$remote_upstream} {$remote_origin}");
        shell_exec("git init --bare --shared=true {$remote_origin}");

        shell_exec("git clone {$remote_upstream} {$remote_deploy}");
        shell_exec("git init --bare --shared=true {$remote_deploy}");

        chdir($storage);

        $remote_deploy = $storage . $ds . 'local_test';
        $cmd = $this->git_live . " init {$remote_origin} {$remote_upstream} {$remote_deploy} {$local_test}";
        shell_exec($cmd);

        chdir($local_test);
        shell_exec('git checkout upstream/develop');
        shell_exec('git checkout -b develop');
        shell_exec('git push origin develop');
        shell_exec('git push deploy develop');
        shell_exec('git checkout master');

        // 変数定義
        $this->remote_upstream_repository = $remote_upstream;
        $this->remote_deploy_repository = $remote_deploy;
        $this->remote_origin_repository = $remote_origin;
        $this->local_test_repository = $local_test;

        // 場所をもとに戻す
        chdir($current_work_dir);
    }

    /**
     * @param string $branch
     * @return \GitLive\Support\Collection
     */
    private function makeArray(string $branch): Collection
    {
        $branch = explode("\n", rtrim($branch));

        array_walk($branch, static function (&$item) {
            $pos = strpos($item, ' -> ') ?: null;
            $item = trim(mb_substr($item, 1, $pos));
        });

        return collect($branch);
    }
}
