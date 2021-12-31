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

namespace Tests\GitLive\Command\Hotfix;

use App;
use GitLive\Application\Application;
use JapaneseDate\DateTime;
use Tests\GitLive\Tester\CommandTestCase as TestCase;
use Tests\GitLive\Tester\CommandTester;
use Tests\GitLive\Tester\CommandTestTrait;
use Tests\GitLive\Tester\MakeGitTestRepoTrait;

/**
 * @internal
 * @coversNothing
 */
class HotfixDestroyCommandTest extends TestCase
{
    use CommandTestTrait;
    use MakeGitTestRepoTrait;

    protected function setUp()
    {
        parent::setUp();

        $this->execCmdToLocalRepo($this->git_live . ' feature start suzunone_branch');
        $this->execCmdToLocalRepo('echo "# new file" > new_text.md');
        $this->execCmdToLocalRepo('git add ./');
        $this->execCmdToLocalRepo('git commit -am "add new file"');
        $this->execCmdToLocalRepo('echo "\n\n * something text" >> README.md');
        $this->execCmdToLocalRepo('git add ./');
        $this->execCmdToLocalRepo('git commit -am "edit readme"');
        $this->execCmdToLocalRepo($this->git_live . ' feature publish');

        $this->execCmdToLocalRepo($this->git_live . ' feature start suzunone_branch_2');
        $this->execCmdToLocalRepo('git checkout develop');
        $this->execCmdToLocalRepo('git merge feature/suzunone_branch');
        $this->execCmdToLocalRepo('git push upstream develop');
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Hotfix\HotfixDestroyCommand
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\HotfixDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecute()
    {
        $this->execCmdToLocalRepo($this->git_live . ' hotfix open unit_test_deploy');
        $application = App::make(Application::class);

        DateTime::setTestNow(DateTime::factory('2018-12-01 22:33:45'));

        $command = $application->find('hotfix:destroy');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        dump($output);
        //$this->assertContains('Already up to date.', $output);
        //$this->assertContains('new branch', $output);
        //$this->assertContains('hotfix/unit_test_deploy -> hotfix/unit_test_deploy', $output);
        $this->assertNotContains('fatal', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.hotfix.prefix.name",
            8 => "git fetch --all",
            9 => "git fetch -p",
            10 => "git fetch upstream",
            11 => "git fetch -p upstream",
            12 => "git fetch deploy",
            13 => "git fetch -p deploy",
            14 => "git remote",
            15 => "git branch -a",
            16 => "git branch -a",
            17 => "git rev-parse --git-dir 2> /dev/null",
            18 => "git config --get gitlive.remote.upstream.readonly",
            19 => "git rev-parse --git-dir 2> /dev/null",
            20 => "git config --get gitlive.remote.deploy.readonly",
            21 => "git push deploy :hotfix/unit_test_deploy",
            22 => "git push upstream :hotfix/unit_test_deploy",
        ], data_get($this->spy, '*.0'));

        $this->assertContains('* hotfix/unit_test_deploy', $this->execCmdToLocalRepo('git branch'));
        $this->assertContains('develop', $this->execCmdToLocalRepo('git branch'));
        // ...
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Hotfix\HotfixDestroyCommand
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\HotfixDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteRemoveLocal()
    {
        $this->execCmdToLocalRepo($this->git_live . ' hotfix open unit_test_deploy');
        $application = App::make(Application::class);

        DateTime::setTestNow(DateTime::factory('2018-12-01 22:33:45'));

        $command = $application->find('hotfix:destroy');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--remove-local' => true,

            // pass arguments to the helper

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        dump($output);
        //$this->assertContains('Already up to date.', $output);
        //$this->assertContains('new branch', $output);
        //$this->assertContains('hotfix/unit_test_deploy -> hotfix/unit_test_deploy', $output);
        $this->assertNotContains('fatal', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.hotfix.prefix.name",
            8 => "git fetch --all",
            9 => "git fetch -p",
            10 => "git fetch upstream",
            11 => "git fetch -p upstream",
            12 => "git fetch deploy",
            13 => "git fetch -p deploy",
            14 => "git remote",
            15 => "git branch -a",
            16 => "git branch -a",
            17 => "git rev-parse --git-dir 2> /dev/null",
            18 => "git config --get gitlive.remote.upstream.readonly",
            19 => "git rev-parse --git-dir 2> /dev/null",
            20 => "git config --get gitlive.remote.deploy.readonly",
            21 => "git push deploy :hotfix/unit_test_deploy",
            22 => "git push upstream :hotfix/unit_test_deploy",
            23 => "git reset --hard HEAD",
            24 => "git rev-parse --git-dir 2> /dev/null",
            25 => "git clean -df",
            26 => "git checkout develop",
            27 => "git branch -d hotfix/unit_test_deploy",
        ], data_get($this->spy, '*.0'));

        $this->assertNotContains('hotfix/unit_test_deploy', $this->execCmdToLocalRepo('git branch'));
        $this->assertContains('* develop', $this->execCmdToLocalRepo('git branch'));
        // ...
    }
}
