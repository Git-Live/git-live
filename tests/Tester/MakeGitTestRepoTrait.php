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
        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        $gitCeilingDir = dirname((string)$this->local_test_repository);
        $env = array_merge(getenv() ?: [], ['GIT_CEILING_DIRECTORIES' => $gitCeilingDir]);
        $process = proc_open(['sh', '-c', $cmd . ' 2>&1'], $descriptorspec, $pipes, $this->local_test_repository, $env);
        $res = '';
        if (is_resource($process)) {
            fclose($pipes[0]);
            $res = stream_get_contents($pipes[1]) . stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
        }

        return $res;
    }

    public function assertHasNotBranch($branch_name)
    {
        $branch_list = $this->makeArray($this->execCmdToLocalRepo('git branch -a'));

        $this->assertFalse($branch_list->search($branch_name) !== false);
    }

    protected function makeGitTestRepoTraitBoot()
    {
        $ds = DIRECTORY_SEPARATOR;
        // PROJECT_ROOT_DIR は bootstrap/bootstrap.php で定義される。
        // phpunit.xml の bootstrap="vendor/autoload.php" 経由で composer の autoload.files に
        // 含まれるため通常は定義済みだが、IDE から単体テストを起動するなどで
        // bootstrap が読み込まれない場合に備えて __DIR__ ベースのフォールバックを用意する。
        $projectRoot = defined('PROJECT_ROOT_DIR') ? PROJECT_ROOT_DIR : dirname(dirname(__DIR__));
        $storage = $projectRoot . $ds . 'storage' . $ds . 'unit_testing';

        // 初期化
        shell_exec("rm -rf {$storage}");

        $remote_origin = $storage . $ds . 'git_live_origin_test.git';
        $remote_upstream = $storage . $ds . 'git_live_upstream_test.git';
        $remote_deploy = $storage . $ds . 'git_live_deploy_test.git';
        $init_working = $storage . $ds . 'init_working';
        $local_test = $storage . $ds . 'local_test';

        shell_exec("git init --bare --shared=true {$remote_upstream}");

        mkdir($init_working);
        $this->execInDir($init_working, 'git init');
        $this->execInDir($init_working, 'git config --local gitlive.branch.develop.name "staging"');
        $this->execInDir($init_working, "git remote add origin {$remote_upstream}");
        $this->execInDir($init_working, 'git remote -v');
        file_put_contents($init_working . $ds . 'README.md', '# unit testing Read me');
        file_put_contents($init_working . $ds . 'LICENSE.md', '# unit testing License');
        $this->execInDir($init_working, 'git add ./');
        $this->execInDir($init_working, 'git commit -am "init"');
        $this->execInDir($init_working, 'git push origin master');
        $this->execInDir($init_working, 'git checkout -b develop');
        $this->execInDir($init_working, 'git push origin develop');
        $this->execInDir($init_working, 'git checkout -b staging');
        $this->execInDir($init_working, 'git push origin staging');

        shell_exec("git clone {$remote_upstream} {$remote_origin}");
        shell_exec("git init --bare --shared=true {$remote_origin}");

        shell_exec("git clone {$remote_upstream} {$remote_deploy}");
        shell_exec("git init --bare --shared=true {$remote_deploy}");

        $remote_deploy = $storage . $ds . 'local_test';
        $cmd = $this->git_live . " init {$remote_origin} {$remote_upstream} {$remote_deploy} {$local_test}";
        $this->execInDir($storage, $cmd);

        $this->execInDir($local_test, 'git checkout upstream/develop');
        $this->execInDir($local_test, 'git config --local gitlive.branch.develop.name "staging"');
        $this->execInDir($local_test, 'git checkout -b develop');
        $this->execInDir($local_test, 'git push origin develop');
        $this->execInDir($local_test, 'git push deploy develop');
        $this->execInDir($local_test, 'git checkout -b staging');
        $this->execInDir($local_test, 'git push origin staging');
        $this->execInDir($local_test, 'git push deploy staging');
        $this->execInDir($local_test, 'git checkout master');

        // 変数定義
        $this->remote_upstream_repository = $remote_upstream;
        $this->remote_deploy_repository = $remote_deploy;
        $this->remote_origin_repository = $remote_origin;
        $this->local_test_repository = $local_test;
    }

    private function execInDir(string $dir, string $cmd): string
    {
        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        $process = proc_open(['sh', '-c', $cmd . ' 2>&1'], $descriptorspec, $pipes, $dir);
        $res = '';
        if (is_resource($process)) {
            fclose($pipes[0]);
            $res = stream_get_contents($pipes[1]) . stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
        }

        return $res;
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
