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

namespace Tests\GitLive\Command\Merge;

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
class StateMasterCommandTest extends TestCase
{
    use CommandTestTrait;
    use MakeGitTestRepoTrait;

    protected function setUp()
    {
        parent::setUp();

        $this->execCmdToLocalRepo($this->git_live . ' feature start suzunone_branch');
        $this->execCmdToLocalRepo($this->git_live . ' feature start suzunone_branch_2');
        $this->execCmdToLocalRepo('echo "# new file" > new_text.md');
        $this->execCmdToLocalRepo('git add ./');
        $this->execCmdToLocalRepo('git commit -am "add new file"');
        $this->execCmdToLocalRepo('echo "\n\n * something text" >> README.md');
        $this->execCmdToLocalRepo('git add ./');
        $this->execCmdToLocalRepo('git commit -am "edit readme"');
        $this->execCmdToLocalRepo('git checkout master');
        $this->execCmdToLocalRepo('git merge feature/suzunone_branch_2');
        $this->execCmdToLocalRepo('git push upstream master');
        $this->execCmdToLocalRepo('git checkout feature/suzunone_branch');
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Merge\StateMasterCommand
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\MergeDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecute()
    {
        $application = App::make(Application::class);

        DateTime::setTestNow(DateTime::factory('2018-12-01 22:33:45'));

        $command = $application->find('merge:state:master');
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
        //$this->assertContains('release/unit_test_deploy -> release/unit_test_deploy', $output);
        $this->assertNotContains('fatal', $output);
        $this->assertContains('Is not conflict.', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.branch.master.name",
            2 => "git fetch --all",
            3 => "git fetch -p",
            4 => "git fetch upstream",
            5 => "git fetch -p upstream",
            6 => "git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/master --stdout",
            7 => "git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/master --stdout| git apply --check",
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Merge\StateMasterCommand
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\MergeDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteError()
    {
        $this->execCmdToLocalRepo('echo "# new file2" > new_text.md');
        $this->execCmdToLocalRepo('git add ./');
        $this->execCmdToLocalRepo('git commit -am "add new file"');
        $this->execCmdToLocalRepo('echo "\n\n * anything text" >> README.md');
        $this->execCmdToLocalRepo('git add ./');
        $this->execCmdToLocalRepo('git commit -am "edit readme"');

        $application = App::make(Application::class);

        DateTime::setTestNow(DateTime::factory('2018-12-01 22:33:45'));

        $command = $application->find('merge:state:master');
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
        //$this->assertContains('release/unit_test_deploy -> release/unit_test_deploy', $output);
        $this->assertNotContains('fatal', $output);
        $this->assertNotContains('Is not conflict.', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.branch.master.name",
            2 => "git fetch --all",
            3 => "git fetch -p",
            4 => "git fetch upstream",
            5 => "git fetch -p upstream",
            6 => "git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/master --stdout",
            7 => "git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/master --stdout| git apply --check",
        ], data_get($this->spy, '*.0'));
    }
}
