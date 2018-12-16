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

namespace Tests\GitLive\Command;

use App;
use GitLive\Application\Application;
use Tests\GitLive\Tester\CommandTestCase as TestCase;
use Tests\GitLive\Tester\CommandTester;
use Tests\GitLive\Tester\CommandTestTrait;
use Tests\GitLive\Tester\MakeGitTestRepoTrait;

/**
 * Class LogCommandTest
 *
 * @category   GitCommand
 * @package    Tests\GitLive\Command
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
class LogCommandTest extends TestCase
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
        $this->execCmdToLocalRepo('echo "\n\n * someting text" >> README.md');
        $this->execCmdToLocalRepo('git add ./');
        $this->execCmdToLocalRepo('git commit -am "edit readme"');
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\LogCommand
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteDevelop()
    {
        $application = App::make(Application::class);

        $command = $application->find('log');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'task' => 'develop',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains("A\tnew_text.md", $output);
        $this->assertContains("M\tREADME.md", $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);
        $this->assertEquals([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.branch.develop.name",
            2 => "git fetch --all",
            3 => "git fetch -p",
            4 => 'git rev-parse --abbrev-ref HEAD 2>/dev/null',
            5 => "git log --pretty=fuller --name-status --left-right upstream/develop..feature/suzunone_branch",
        ], data_get($this->spy, '*.0'));

        // ...
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Application\Application
     * @covers \GitLive\Command\CommandBase
     * @covers \GitLive\Command\LogCommand
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecuteMaster()
    {
        $application = App::make(Application::class);

        $command = $application->find('log');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'task' => 'master',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains("A\tnew_text.md", $output);
        $this->assertContains("M\tREADME.md", $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);
        $this->assertEquals([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.branch.master.name",
            2 => "git fetch --all",
            3 => "git fetch -p",
            4 => 'git rev-parse --abbrev-ref HEAD 2>/dev/null',
            5 => "git log --pretty=fuller --name-status --left-right upstream/master..feature/suzunone_branch",
        ], data_get($this->spy, '*.0'));

        // ...
    }
}
