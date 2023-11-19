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
 * Class FeatureCloseCommandTest
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
class FeatureCloseCommandTest extends TestCase
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
     * @covers \GitLive\Command\Feature\FeatureCloseCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecute()
    {
        $application = App::make(Application::class);

        $command = $application->find('feature:close');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            // 'feature_name' => 'suzunone_branch_new',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertEquals('', $output);

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
            8 => 'git rev-parse --abbrev-ref HEAD 2>/dev/null',
            9 => 'git push upstream :feature/suzunone_branch',
            10 => 'git push origin :feature/suzunone_branch',
            11 => 'git rev-parse --git-dir 2> /dev/null',
            12 => 'git config --get gitlive.branch.develop.name',
            13 => 'git checkout develop',
            14 => 'git branch -D feature/suzunone_branch',
        ], data_get($this->spy, '*.0'));

        $this->assertHasBranch('feature/suzunone_branch_2');
        $this->assertHasNotBranch('feature/suzunone_branch');
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\FeatureCloseCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteWithFeature()
    {
        $application = App::make(Application::class);

        $command = $application->find('feature:close');
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
        $this->assertEquals('', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            'git rev-parse --git-dir 2> /dev/null',
            'git config --get gitlive.branch.feature.prefix.ignore',
            'git rev-parse --git-dir 2> /dev/null',
            'git config --get gitlive.branch.feature.prefix.name',
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            // "git rev-parse --abbrev-ref HEAD 2>/dev/null",
            'git push upstream :feature/suzunone_branch_2',
            'git push origin :feature/suzunone_branch_2',
            'git rev-parse --git-dir 2> /dev/null',
            'git config --get gitlive.branch.develop.name',
            'git checkout develop',
            'git branch -D feature/suzunone_branch_2',
        ], data_get($this->spy, '*.0'));

        $this->assertHasNotBranch('feature/suzunone_branch_2');
        $this->assertHasBranch('feature/suzunone_branch');
    }
}
