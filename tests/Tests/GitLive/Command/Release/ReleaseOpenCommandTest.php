<?php
/**
 * ReleaseOpenCommandTest.php
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-16
 */

namespace Tests\GitLive\Command\Release;


use App;
use GitLive\Application\Application;
use JapaneseDate\DateTime;
use Tests\GitLive\Tester\CommandTestCase as TestCase;
use Tests\GitLive\Tester\CommandTester;
use Tests\GitLive\Tester\CommandTestTrait;
use Tests\GitLive\Tester\MakeGitTestRepoTrait;

class ReleaseOpenCommandTest extends TestCase
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
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Service\CommandLineKernelService
     */
    public function testExecute()
    {
        $this->execCmdToLocalRepo('git push upstream feature/suzunone_branch');
        $this->execCmdToLocalRepo('git push origin feature/suzunone_branch');
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
        //$this->assertContains('new branch', $output);
        //$this->assertContains('feature/suzunone_branch -> feature/suzunone_branch', $output);

        dump($output);
        //$this->assertContains('Already up to date.', $output);
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
            15 => "git branch -a",
            16 => "git rev-parse --git-dir 2> /dev/null",
            17 => "git config --get gitlive.branch.hotfix.prefix.name",
            18 => "git branch -a",
            19 => "git branch -a",
            20 => "git checkout upstream/develop",
            21 => "git checkout -b release/20181201223345",
            22 => "git push upstream release/20181201223345",
            23 => "git push deploy release/20181201223345",
        ], data_get($this->spy, '*.0'));

        // ...
    }

}
