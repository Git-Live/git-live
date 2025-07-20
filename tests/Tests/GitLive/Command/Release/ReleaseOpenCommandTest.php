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

namespace Tests\GitLive\Command\Release;

use GitLive\Application\Application;
use GitLive\Application\Facade as App;
use GitLive\Exception;
use JapaneseDate\DateTime;
use Tests\GitLive\Tester\CommandTestCase as TestCase;
use Tests\GitLive\Tester\CommandTester;
use Tests\GitLive\Tester\CommandTestTrait;
use Tests\GitLive\Tester\MakeGitTestRepoTrait;

/**
 * @internal
 * @coversNothing
 */
class ReleaseOpenCommandTest extends TestCase
{
    use CommandTestTrait;
    use MakeGitTestRepoTrait;

    protected function setUp(): void
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
     * @covers \GitLive\Command\Release\ReleaseOpenCommand
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\ReleaseDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecute()
    {
        $application = App::make(Application::class);

        DateTime::setTestNow(DateTime::factory('2018-12-01 22:33:45'));

        $command = $application->find('release:open');
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
        $this->assertContains('new branch', $output);
        $this->assertContains('release/20181201223345 -> release/20181201223345', $output);
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
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git fetch --all",
            9 => "git fetch -p",
            10 => "git fetch upstream",
            11 => "git fetch -p upstream",
            12 => "git fetch deploy",
            13 => "git fetch -p deploy",
            14 => "git remote",
            15 => "git branch -a --no-color",
            16 => "git rev-parse --git-dir 2> /dev/null",
            17 => "git config --get gitlive.branch.hotfix.prefix.name",
            18 => "git branch -a --no-color",
            19 => "git rev-parse --git-dir 2> /dev/null",
            20 => "git config --get gitlive.remote.upstream.readonly",
            21 => "git rev-parse --git-dir 2> /dev/null",
            22 => "git config --get gitlive.remote.deploy.readonly",
            23 => "git branch -a --no-color",
            24 => "git checkout upstream/staging",
            25 => "git checkout -b release/20181201223345",
            26 => "git push upstream release/20181201223345",
            27 => "git push deploy release/20181201223345",
        ], data_get($this->spy, '*.0'));

        $this->assertContains('* release/20181201223345', $this->execCmdToLocalRepo('git branch --no-color'));
        // ...
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Release\ReleaseOpenCommand
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\ReleaseDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteWithName()
    {
        $application = App::make(Application::class);

        DateTime::setTestNow(DateTime::factory('2018-12-01 22:33:45'));

        $command = $application->find('release:open');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            'name' => 'ut_release'
            // pass arguments to the helper

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        dump($output);
        //$this->assertContains('Already up to date.', $output);
        $this->assertContains('new branch', $output);
        $this->assertContains('release/ut_release -> release/ut_release', $output);
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
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git fetch --all",
            9 => "git fetch -p",
            10 => "git fetch upstream",
            11 => "git fetch -p upstream",
            12 => "git fetch deploy",
            13 => "git fetch -p deploy",
            14 => "git remote",
            15 => "git branch -a --no-color",
            16 => "git rev-parse --git-dir 2> /dev/null",
            17 => "git config --get gitlive.branch.hotfix.prefix.name",
            18 => "git branch -a --no-color",
            19 => "git rev-parse --git-dir 2> /dev/null",
            20 => "git config --get gitlive.remote.upstream.readonly",
            21 => "git rev-parse --git-dir 2> /dev/null",
            22 => "git config --get gitlive.remote.deploy.readonly",
            23 => "git branch -a --no-color",
            24 => "git checkout upstream/staging",
            25 => "git checkout -b release/ut_release",
            26 => "git push upstream release/ut_release",
            27 => "git push deploy release/ut_release",
        ], data_get($this->spy, '*.0'));

        $this->assertContains('* release/ut_release', $this->execCmdToLocalRepo('git branch --no-color'));
        // ...
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Release\ReleaseOpenCommand
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\ReleaseDriver
     * @covers \GitLive\Service\CommandLineKernelService
     *
     */
    public function testExecuteDuplicateRelease()
    {
        $this->expectException(\GitLive\Driver\Exception::class);
        $this->execCmdToLocalRepo($this->git_live . ' release:open');

        $application = App::make(Application::class);

        DateTime::setTestNow(DateTime::factory('2018-12-01 22:33:45'));

        $command = $application->find('release:open');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            'name' => 'ut_release'
            // pass arguments to the helper

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        dump($output);
        //$this->assertContains('Already up to date.', $output);
        $this->assertContains('new branch', $output);
        $this->assertContains('release/ut_release -> release/ut_release', $output);
        $this->assertNotContains('fatal', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        // ...
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Release\ReleaseOpenCommand
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\ReleaseDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteDuplicateHotfix()
    {
        $application = App::make(Application::class);

        DateTime::setTestNow(DateTime::factory('2018-12-01 22:33:45'));

        $command = $application->find('release:open');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            'name' => 'ut_release'
            // pass arguments to the helper

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        dump($output);
        //$this->assertContains('Already up to date.', $output);
        $this->assertContains('new branch', $output);
        $this->assertContains('release/ut_release -> release/ut_release', $output);
        $this->assertNotContains('fatal', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        // ...
    }
}
