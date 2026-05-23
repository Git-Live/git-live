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

namespace Tests\GitLive\Command\Log;

use GitLive\Application\Application;
use GitLive\Application\Facade as App;
use JapaneseDate\DateTime;
use Tests\GitLive\Tester\CommandTestCase as TestCase;
use Tests\GitLive\Tester\CommandTester;
use Tests\GitLive\Tester\CommandTestTrait;
use Tests\GitLive\Tester\MakeGitTestRepoTrait;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\GitLive\Application\Application::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\GitLive\Command\CommandBase::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\GitLive\Command\Log\BaseLogCommand::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\GitLive\Command\Log\LogDevelopCommand::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\GitLive\Driver\DriverBase::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\GitLive\Driver\MergeDriver::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\GitLive\Service\CommandLineKernelService::class)]
#[\PHPUnit\Framework\Attributes\CoversNothing]
class LogDevelopCommandTest extends TestCase
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
        $this->execCmdToLocalRepo('git checkout feature/suzunone_branch');
    }

    /**
     * @throws \Exception
     */
    public function testExecute()
    {
        $application = App::make(Application::class);

        DateTime::setTestNow(DateTime::factory('2018-12-01 22:33:45'));

        $command = $application->find('log:develop');
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
        $this->assertStringNotContainsString('fatal', $output);
        $this->assertStringContainsString('edit readme', $output);
        $this->assertStringContainsString('add new file', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.develop.name',
            2 => 'git fetch --all',
            3 => 'git fetch -p',
            4 => 'git rev-parse --abbrev-ref HEAD 2> /dev/null',
            5 => 'git log --left-right upstream/staging..feature/suzunone_branch',
        ], data_get($this->spy, '*.0'));

        // ...
    }

    /**
     * @throws \Exception
     */
    public function testExecuteDefaultOption()
    {
        $application = App::make(Application::class);

        DateTime::setTestNow(DateTime::factory('2018-12-01 22:33:45'));

        $command = $application->find('log:develop');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),

            '--pretty' => 'fuller',
            '--name-status' => true,
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
        $this->assertStringNotContainsString('fatal', $output);
        $this->assertStringContainsString('edit readme', $output);
        $this->assertStringContainsString('add new file', $output);
        $this->assertStringContainsString('README.md', $output);
        $this->assertStringContainsString('new_text.md', $output);

        dump($this->spy);
        dump(data_get($this->spy, '*.0'));
        dump($output);

        $this->assertEquals([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.develop.name',
            2 => 'git fetch --all',
            3 => 'git fetch -p',
            4 => 'git rev-parse --abbrev-ref HEAD 2> /dev/null',
            5 => 'git log --pretty=fuller --name-status --left-right upstream/staging..feature/suzunone_branch',
        ], data_get($this->spy, '*.0'));

        // ...
    }
}
