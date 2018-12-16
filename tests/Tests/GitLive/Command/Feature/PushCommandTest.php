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

use App;
use GitLive\Application\Application;
use Tests\GitLive\Tester\CommandTestCase as TestCase;
use Tests\GitLive\Tester\CommandTester;
use Tests\GitLive\Tester\CommandTestTrait;
use Tests\GitLive\Tester\MakeGitTestRepoTrait;

/**
 * @internal
 * @coversNothing
 */
class PushCommandTest extends TestCase
{
    use CommandTestTrait;
    use MakeGitTestRepoTrait;

    protected function setUp()
    {
        parent::setUp();

        $this->execCmdToLocalRepo($this->git_live . ' feature start suzunone_branch');
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\FeaturePushCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecute()
    {
        $application = App::make(Application::class);

        $command = $application->find('feature:push');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        //$this->assertContains('new branch', $output);
        //$this->assertContains('feature/suzunone_branch -> feature/suzunone_branch', $output);

        dump($output);
        $this->assertContains('feature/suzunone_branch -> feature/suzunone_branch', $output);
        $this->assertContains('[new branch] ', $output);
        $this->assertNotContains('fatal', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.branch.feature.prefix.ignore",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.feature.prefix.name",
            4 => "git fetch --all",
            5 => "git fetch -p",
            6 => "git fetch upstream",
            7 => "git fetch -p upstream",
            8 => "git symbolic-ref HEAD 2>/dev/null",
            9 => "git push origin refs/heads/feature/suzunone_branch",
        ], data_get($this->spy, '*.0'));

        // ...
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\FeaturePushCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteDevelop()
    {
        $application = App::make(Application::class);

        $this->execCmdToLocalRepo('git checkout develop');

        $command = $application->find('feature:push');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        //$this->assertContains('new branch', $output);
        //$this->assertContains('feature/suzunone_branch -> feature/suzunone_branch', $output);

        $this->assertContains('Everything up-to-date', $output);
        $this->assertNotContains('fatal', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.branch.feature.prefix.ignore",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.feature.prefix.name",
            4 => "git fetch --all",
            5 => "git fetch -p",
            6 => "git fetch upstream",
            7 => "git fetch -p upstream",
            8 => "git symbolic-ref HEAD 2>/dev/null",
            9 => "git push origin refs/heads/develop",
        ], data_get($this->spy, '*.0'));

        // ...
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\FeaturePushCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteMaster()
    {
        $application = App::make(Application::class);

        $this->execCmdToLocalRepo('git checkout master');

        $command = $application->find('feature:push');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        //$this->assertContains('new branch', $output);
        //$this->assertContains('feature/suzunone_branch -> feature/suzunone_branch', $output);

        $this->assertContains('Everything up-to-date', $output);
        $this->assertNotContains('fatal', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.branch.feature.prefix.ignore",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.feature.prefix.name",
            4 => "git fetch --all",
            5 => "git fetch -p",
            6 => "git fetch upstream",
            7 => "git fetch -p upstream",
            8 => "git symbolic-ref HEAD 2>/dev/null",
            9 => "git push origin refs/heads/master",
        ], data_get($this->spy, '*.0'));

        // ...
    }
}
