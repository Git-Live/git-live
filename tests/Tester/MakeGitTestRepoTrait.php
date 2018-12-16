<?php
/**
 * MakeGitTestRepoTrait.phpt.php
 *
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
 * @since      2018-12-16
 */

namespace Tests\GitLive\Tester;


trait MakeGitTestRepoTrait
{
    protected $remote_origin_repository,$remote_upstream_repository,$remote_deployrepository,$local_test_repository;

    protected function makeGitTestRepoTraitBoot()
    {
        // カレント取得
        $current_work_dir = getcwd();

        $ds = DIRECTORY_SEPARATOR;
        $storage = PROJECT_ROOT_DIR.$ds.'storage'.$ds.'unit_testing';

        // 初期化
        $cmd = "rm -rf {$storage}";
        `$cmd`;

        $remote_origin = $storage.$ds.'git_live_origin_test.git';
        $remote_upstream = $storage.$ds.'git_live_upstream_test.git';
        $remote_deploy = $storage.$ds.'git_live_deploy_test.git';
        $init_working  = $storage.$ds.'init_working';
        $local_test  = $storage.$ds.'local_test';

        echo `git init --bare --shared=true $remote_upstream`;


        mkdir($init_working);
        chdir($init_working);
        echo `git init`;
        echo `git remote add origin $remote_upstream`;
        echo `git remote -v`;
        file_put_contents($init_working.$ds.'README.md', '# unit testing');
        echo `git add ./`;
        echo `git commit -am "init"`;
        echo `git push origin master`;
        echo `git checkout -b develop`;
        echo `git push origin develop`;

        echo `git clone $remote_upstream $remote_origin`;
        echo `git init --bare --shared=true $remote_origin`;

        echo `git clone $remote_upstream $remote_deploy`;
        echo `git init --bare --shared=true $remote_deploy`;



        chdir($storage);

        $remote_deploy = $storage.$ds.'local_test';
        $cmd = "git live init {$remote_origin} {$remote_upstream} {$remote_deploy} {$local_test}";

        `$cmd`;


        // 変数定義
        $this->remote_upstream_repository = $remote_upstream;
        $this->remote_deployrepository = $remote_deploy;
        $this->remote_origin_repository = $remote_origin;
        $this->local_test_repository = $local_test;


        // 場所をもとに戻す
        chdir($current_work_dir);
    }

    public function assertHasBranch($branch_name)
    {
        // カレント取得
        $current_work_dir = getcwd();

        chdir($this->local_test_repository);

        $branch_list = $this->makeArray(`git branch`);


        // 場所をもとに戻す

        $this->assertTrue($branch_list->search($branch_name) !== false);
    }


    private function makeArray($branch)
    {
        $branch = explode("\n", rtrim($branch));

        array_walk($branch, function (&$item) {
            $pos = strpos($item, ' -> ') ?: null;
            $item = trim(mb_substr($item, 1, $pos));
        });

        return collect($branch);
    }

}