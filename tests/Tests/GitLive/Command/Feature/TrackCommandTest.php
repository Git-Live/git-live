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

namespace Tests\GitLive\Command\Feature;

use GitLive\Application\Application;
use GitLive\Application\Facade as App;
use Tests\GitLive\Tester\CommandTestCase as TestCase;
use Tests\GitLive\Tester\CommandTester;
use Tests\GitLive\Tester\CommandTestTrait;
use Tests\GitLive\Tester\MakeGitTestRepoTrait;

/**
 * @internal
 * @coversNothing
 */
class TrackCommandTest extends TestCase
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
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\TrackCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecute()
    {
        $this->execCmdToLocalRepo('git branch -D  feature/suzunone_branch');
        $application = App::make(Application::class);

        $command = $application->find('feature:track');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper

            'feature_name' => 'suzunone_branch',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        //$this->assertContains('new branch', $output);
        //$this->assertContains('feature/suzunone_branch -> feature/suzunone_branch', $output);

        dump($output);
        // $this->assertContains('feature/suzunone_branch -> feature/suzunone_branch', $output);
        // $this->assertContains('[new branch] ', $output);
        $this->assertNotContains('fatal', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.feature.prefix.ignore',
            2 => 'git rev-parse --git-dir 2> /dev/null',
            3 => 'git config --get gitlive.branch.feature.prefix.name',
            4 => 'git fetch --all',
            5 => 'git fetch -p',
            6 => 'git fetch upstream',
            7 => 'git fetch -p upstream',
            8 => 'git rev-parse --abbrev-ref HEAD 2> /dev/null',
            9 => 'git branch -a --no-color',
            10 => 'git checkout upstream/feature/suzunone_branch',
            11 => 'git checkout -b feature/suzunone_branch',
            12 => 'git pull upstream feature/suzunone_branch',
        ], data_get($this->spy, '*.0'));

        $this->assertContains('* feature/suzunone_branch', $this->execCmdToLocalRepo('git branch --no-color'));
        $this->assertCount(3, explode("\n", trim($this->execCmdToLocalRepo('git log --oneline'))));

        // 余計なブランチを更新していないかどうか
        $this->execCmdToLocalRepo('git checkout feature/suzunone_branch_2');
        $this->assertCount(1, explode("\n", trim($this->execCmdToLocalRepo('git log --oneline'))));

        // ...
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\TrackCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteSelf()
    {
        $this->execCmdToLocalRepo('git checkout  feature/suzunone_branch');
        $application = App::make(Application::class);

        $command = $application->find('feature:track');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper

            'feature_name' => 'suzunone_branch',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        //$this->assertContains('new branch', $output);
        //$this->assertContains('feature/suzunone_branch -> feature/suzunone_branch', $output);

        dump($output);
        // $this->assertContains('feature/suzunone_branch -> feature/suzunone_branch', $output);
        // $this->assertContains('[new branch] ', $output);
        $this->assertNotContains('fatal', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.feature.prefix.ignore',
            2 => 'git rev-parse --git-dir 2> /dev/null',
            3 => 'git config --get gitlive.branch.feature.prefix.name',
            4 => 'git fetch --all',
            5 => 'git fetch -p',
            6 => 'git fetch upstream',
            7 => 'git fetch -p upstream',
            8 => 'git rev-parse --abbrev-ref HEAD 2> /dev/null',
            9 => 'git branch -a --no-color',
            10 => 'git pull upstream feature/suzunone_branch',
        ], data_get($this->spy, '*.0'));

        $this->assertContains('* feature/suzunone_branch', $this->execCmdToLocalRepo('git branch --no-color'));
        $this->assertCount(3, explode("\n", trim($this->execCmdToLocalRepo('git log --oneline'))));

        // 余計なブランチを更新していないかどうか
        $this->execCmdToLocalRepo('git checkout feature/suzunone_branch_2');
        $this->assertCount(1, explode("\n", trim($this->execCmdToLocalRepo('git log --oneline'))));

        // ...
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\TrackCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteHasBranch()
    {
        $application = App::make(Application::class);

        $command = $application->find('feature:track');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper

            'feature_name' => 'suzunone_branch',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        //$this->assertContains('new branch', $output);
        //$this->assertContains('feature/suzunone_branch -> feature/suzunone_branch', $output);

        dump($output);
        // $this->assertContains('feature/suzunone_branch -> feature/suzunone_branch', $output);
        // $this->assertContains('[new branch] ', $output);
        $this->assertNotContains('fatal', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.feature.prefix.ignore',
            2 => 'git rev-parse --git-dir 2> /dev/null',
            3 => 'git config --get gitlive.branch.feature.prefix.name',
            4 => 'git fetch --all',
            5 => 'git fetch -p',
            6 => 'git fetch upstream',
            7 => 'git fetch -p upstream',
            8 => 'git rev-parse --abbrev-ref HEAD 2> /dev/null',
            9 => 'git checkout upstream/feature/suzunone_branch',
            9 => 'git branch -a --no-color',
            10 => 'git checkout feature/suzunone_branch',
            11 => 'git pull upstream feature/suzunone_branch',
        ], data_get($this->spy, '*.0'));

        $this->assertContains('* feature/suzunone_branch', $this->execCmdToLocalRepo('git branch --no-color'));
        $this->assertCount(3, explode("\n", trim($this->execCmdToLocalRepo('git log --oneline'))));

        // 余計なブランチを更新していないかどうか
        $this->execCmdToLocalRepo('git checkout feature/suzunone_branch_2');
        $this->assertCount(1, explode("\n", trim($this->execCmdToLocalRepo('git log --oneline'))));

        // ...
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\TrackCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     *
     */
    public function testExecuteError()
    {
        $this->expectException(\GitLive\Driver\Exception::class);
        $application = App::make(Application::class);

        $command = $application->find('feature:track');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper

            'feature_name' => 'suzunone_branch_2',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        //$this->assertContains('new branch', $output);
        //$this->assertContains('feature/suzunone_branch -> feature/suzunone_branch', $output);

        dump($output);
        // $this->assertContains('feature/suzunone_branch -> feature/suzunone_branch', $output);
        // $this->assertContains('[new branch] ', $output);
        $this->assertNotContains('fatal', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.feature.prefix.ignore',
            2 => 'git rev-parse --git-dir 2> /dev/null',
            3 => 'git config --get gitlive.branch.feature.prefix.name',
            4 => 'git fetch --all',
            5 => 'git fetch -p',
            6 => 'git fetch upstream',
            7 => 'git fetch -p upstream',
            8 => 'git rev-parse --abbrev-ref HEAD 2> /dev/null',
            9 => 'git checkout upstream/feature/suzunone_branch',
            9 => 'git branch -a --no-color',
            10 => 'git checkout feature/suzunone_branch',
            11 => 'git pull upstream feature/suzunone_branch',
        ], data_get($this->spy, '*.0'));

        $this->assertContains('* feature/suzunone_branch', $this->execCmdToLocalRepo('git branch --no-color'));
        $this->assertCount(3, explode("\n", trim($this->execCmdToLocalRepo('git log --oneline'))));

        // 余計なブランチを更新していないかどうか
        $this->execCmdToLocalRepo('git checkout feature/suzunone_branch_2');
        $this->assertCount(1, explode("\n", trim($this->execCmdToLocalRepo('git log --oneline'))));

        // ...
    }
}
