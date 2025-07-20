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

namespace Tests\GitLive\Command\Config;

use GitLive\Application\Facade as App;
use GitLive\Application\Application;
use Tests\GitLive\Tester\CommandTestCase as TestCase;
use Tests\GitLive\Tester\CommandTester;
use Tests\GitLive\Tester\CommandTestTrait;
use Tests\GitLive\Tester\MakeGitTestRepoTrait;

/**
 * Class SetCommandTest
 *
 * @category   GitCommand
 * @package    Tests\GitLive\Command\Config
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
class SetCommandTest extends TestCase
{
    use CommandTestTrait;

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Config\SetCommand
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecute()
    {
        $application = App::make(Application::class);

        $command = $application->find('config:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'name' => 'branch.develop.name',
            'value' => 'staging',

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
            'git rev-parse --git-dir 2> /dev/null',
            'git config --local gitlive.branch.develop.name "staging"',
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Config\SetCommand
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteLocal()
    {
        $application = App::make(Application::class);

        $command = $application->find('config:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'name' => 'branch.develop.name',
            'value' => 'staging',
            '--local' => true,

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
            'git rev-parse --git-dir 2> /dev/null',
            'git config --local gitlive.branch.develop.name "staging"',
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Config\SetCommand
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteGlobal()
    {
        $application = App::make(Application::class);

        $command = $application->find('config:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'name' => 'branch.develop.name',
            'value' => 'staging',
            '--global' => true,

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
            'git rev-parse --git-dir 2> /dev/null',
            'git config --global gitlive.branch.develop.name "staging"',
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\Config\SetCommand
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteSystem()
    {
        $application = App::make(Application::class);

        $command = $application->find('config:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'name' => 'branch.develop.name',
            'value' => 'staging',
            '--system' => true,

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
            'git rev-parse --git-dir 2> /dev/null',
            'git config --system gitlive.branch.develop.name "staging"',
        ], data_get($this->spy, '*.0'));
    }
}
