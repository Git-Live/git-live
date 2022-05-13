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
 * Class FeatureStatusCommandTest
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
class FeatureStatusCommandTest extends TestCase
{
    use CommandTestTrait;
    use MakeGitTestRepoTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->execCmdToLocalRepo($this->git_live . ' feature start suzunone_branch_2');

        $this->execCmdToLocalRepo($this->git_live . ' feature push');
        $this->execCmdToLocalRepo($this->git_live . ' feature publish');
        $this->execCmdToLocalRepo($this->git_live . ' feature start suzunone_branch');
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\FeatureStatusCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecute()
    {
        $application = App::make(Application::class);

        $command = $application->find('feature:status');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            //'feature_name' => 'suzunone_branch',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('', $output);
        $this->assertNotContains('fatal', $output);

        dump($this->spy);

        dump(data_get($this->spy, '*.0'));
        $this->assertEquals([
            0 => 'git rev-parse --abbrev-ref HEAD 2> /dev/null',
            1 => 'git rev-parse --git-dir 2> /dev/null',
            2 => 'git config --get gitlive.branch.master.name',
            3 => 'git rev-parse --git-dir 2> /dev/null',
            4 => 'git config --get gitlive.branch.develop.name',
            5 => 'git rev-parse --git-dir 2> /dev/null',
            6 => 'git config --get gitlive.branch.hotfix.prefix.name',
            7 => 'git rev-parse --git-dir 2> /dev/null',
            8 => 'git config --get gitlive.branch.release.prefix.name',
            9 => 'git diff staging --name-status',
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\FeatureStatusCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteWithFeatureNameReal()
    {
        $this->execCmdToLocalRepo('touch test_file.md');
        $this->execCmdToLocalRepo('git add ./');
        $this->execCmdToLocalRepo('git commit -am "test commit"');

        $application = App::make(Application::class);

        $command = $application->find('feature:status');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'feature_name' => 'feature/suzunone_branch_2',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertContains('A	test_file.md', $output);

        dump($this->spy);

        dump(data_get($this->spy, '*.0'));
        $this->assertEquals([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.hotfix.prefix.name',
            2 => 'git rev-parse --git-dir 2> /dev/null',
            3 => 'git config --get gitlive.branch.release.prefix.name',
            4 => 'git rev-parse --git-dir 2> /dev/null',
            5 => 'git config --get gitlive.branch.feature.prefix.ignore',
            6 => 'git rev-parse --git-dir 2> /dev/null',
            7 => 'git config --get gitlive.branch.feature.prefix.name',
            8 => 'git diff feature/suzunone_branch_2 --name-status',
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\FeatureStatusCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteWithFeatureName()
    {
        $this->execCmdToLocalRepo('touch test_file.md');
        $this->execCmdToLocalRepo('git add ./');
        $this->execCmdToLocalRepo('git commit -am "test commit"');

        $application = App::make(Application::class);

        $command = $application->find('feature:status');
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

        $this->assertContains('A	test_file.md', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.hotfix.prefix.name',
            2 => 'git rev-parse --git-dir 2> /dev/null',
            3 => 'git config --get gitlive.branch.release.prefix.name',
            4 => 'git rev-parse --git-dir 2> /dev/null',
            5 => 'git config --get gitlive.branch.feature.prefix.ignore',
            6 => 'git rev-parse --git-dir 2> /dev/null',
            7 => 'git config --get gitlive.branch.feature.prefix.name',
            8 => 'git diff feature/suzunone_branch_2 --name-status',
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\FeatureStatusCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteMaster()
    {
        $this->execCmdToLocalRepo('git checkout master');

        $application = App::make(Application::class);

        $command = $application->find('feature:status');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertEquals('', trim($output));

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => 'git rev-parse --abbrev-ref HEAD 2> /dev/null',
            1 => 'git rev-parse --git-dir 2> /dev/null',
            2 => 'git config --get gitlive.branch.master.name',
            3 => 'git rev-parse --git-dir 2> /dev/null',
            4 => 'git config --get gitlive.branch.develop.name',
            5 => 'git diff staging --name-status',
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\FeatureStatusCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteDevelop()
    {
        $this->execCmdToLocalRepo('git checkout develop');

        $application = App::make(Application::class);

        $command = $application->find('feature:status');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertEquals('', trim($output));

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => "git rev-parse --abbrev-ref HEAD 2> /dev/null",
            1 => "git rev-parse --git-dir 2> /dev/null",
            2 => "git config --get gitlive.branch.master.name",
            3 => "git rev-parse --git-dir 2> /dev/null",
            4 => "git config --get gitlive.branch.develop.name",
            5 => "git rev-parse --git-dir 2> /dev/null",
            6 => "git config --get gitlive.branch.hotfix.prefix.name",
            7 => "git rev-parse --git-dir 2> /dev/null",
            8 => "git config --get gitlive.branch.release.prefix.name",
            9 => "git diff staging --name-status",
        ], data_get($this->spy, '*.0'));
    }
}
