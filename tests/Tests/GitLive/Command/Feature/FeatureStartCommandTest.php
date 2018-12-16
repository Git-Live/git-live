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
use GitLive\Exception;
use Tests\GitLive\Tester\CommandTestCase as TestCase;
use Tests\GitLive\Tester\CommandTester;
use Tests\GitLive\Tester\CommandTestTrait;
use Tests\GitLive\Tester\MakeGitTestRepoTrait;

/**
 * @internal
 * @coversNothing
 */
class FeatureStartCommandTest extends TestCase
{
    use CommandTestTrait;
    use MakeGitTestRepoTrait;

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\FeatureStartCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecute()
    {
        $application = App::make(Application::class);

        $command = $application->find('feature:start');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'feature_name' => 'suzunone_branch_new',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('', $output);

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
            8 => 'git branch -a',
            9 => 'git rev-parse --git-dir 2> /dev/null',
            10 => 'git config --get gitlive.branch.develop.name',
            11 => 'git checkout upstream/develop',
            12 => 'git checkout -b feature/suzunone_branch_new',
        ], data_get($this->spy, '*.0'));

        $this->assertHasBranch('feature/suzunone_branch_new');
    }

    /**
     * duplicate feature test
     *
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Feature\FeatureStartCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     * @expectedException Exception
     */
    public function testExecuteError()
    {
        $application = App::make(Application::class);

        $command = $application->find('feature:start');
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
        $this->assertContains('', $output);

        $command = $application->find('feature:start');
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
        $this->assertContains('', $output);
    }
}
