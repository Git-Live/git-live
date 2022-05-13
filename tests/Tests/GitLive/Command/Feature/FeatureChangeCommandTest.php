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
 * Class FeatureChangeCommandTest
 *
 * @category   GitCommand
 * @package    Tests\GitLive\Command\Feature
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-16
 * @internal
 * @coversNothing
 */
class FeatureChangeCommandTest extends TestCase
{
    use CommandTestTrait;
    use MakeGitTestRepoTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->execCmdToLocalRepo($this->git_live . ' feature start suzunone_branch');

        $this->execCmdToLocalRepo($this->git_live . ' feature push');
        $this->execCmdToLocalRepo($this->git_live . ' feature publish');

        $this->execCmdToLocalRepo($this->git_live . ' feature start suzunone_branch2');
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\ChangeCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecute()
    {
        $this->execCmdToLocalRepo('git branch -D feature/suzunone_branch');
        $application = App::make(Application::class);

        $command = $application->find('feature:change');
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
        $this->assertContains("Switched to a new branch 'feature/suzunone_branch'", $output);
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
            6 => 'git branch -a --no-color',
            7 => 'git rev-parse --git-dir 2> /dev/null',
            8 => 'git config --get gitlive.branch.master.name',
            9 => 'git rev-parse --git-dir 2> /dev/null',
            10 => 'git config --get gitlive.branch.develop.name',
            11 => 'git checkout remotes/origin/feature/suzunone_branch',
            12 => 'git checkout -b feature/suzunone_branch',
        ], data_get($this->spy, '*.0'));

        // ...
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\ChangeCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteForce()
    {
        $this->execCmdToLocalRepo('git branch -D feature/suzunone_branch');
        $application = App::make(Application::class);

        $command = $application->find('feature:change');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'feature_name' => 'suzunone_branch',
            '-f' => true,

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains("Switched to a new branch 'feature/suzunone_branch'", $output);
        $this->assertNotContains('fatal', $output);

        dump(data_get($this->spy, '*.0'));
        $this->assertEquals([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.feature.prefix.ignore',
            2 => 'git rev-parse --git-dir 2> /dev/null',
            3 => 'git config --get gitlive.branch.feature.prefix.name',
            4 => 'git fetch --all',
            5 => 'git fetch -p',
            6 => 'git branch -a --no-color',
            7 => 'git rev-parse --git-dir 2> /dev/null',
            8 => 'git config --get gitlive.branch.master.name',
            9 => 'git rev-parse --git-dir 2> /dev/null',
            10 => 'git config --get gitlive.branch.develop.name',
            11 => 'git checkout --force remotes/origin/feature/suzunone_branch',
            12 => 'git checkout --force -b feature/suzunone_branch',
        ], data_get($this->spy, '*.0'));

        // ...
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\ChangeCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteSimple()
    {
        $application = App::make(Application::class);

        $command = $application->find('feature:change');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'feature_name' => 'suzunone_branch',
            '-f' => true,

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains("Switched to branch 'feature/suzunone_branch'", $output);
        $this->assertNotContains('fatal', $output);

        dump(data_get($this->spy, '*.0'));
        $this->assertEquals([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.feature.prefix.ignore',
            2 => 'git rev-parse --git-dir 2> /dev/null',
            3 => 'git config --get gitlive.branch.feature.prefix.name',
            4 => 'git fetch --all',
            5 => 'git fetch -p',
            6 => 'git branch -a --no-color',
            7 => 'git checkout --force feature/suzunone_branch',
        ], data_get($this->spy, '*.0'));

        // ...
    }
}
