<?php
/**
 * ReleaseDriverTest.php
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      2018/11/25
 */

namespace Tests\GitLive\Driver;

use GitLive\Driver\HotfixDriver;
use GitLive\Driver\ReleaseDriver;
use Tests\GitLive\TestCase;
use GitLive\Application\Container;
use GitLive\Support\SystemCommandInterface;
use GitLive\Mock\SystemCommand;
use App;

class HptfixDriverTest extends TestCase
{
    /**
     * @return array
     * @throws \ReflectionException
     */
    public function testIsBuildOpen()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            //->once()
            ->with('git config --get gitlive.branch.hotfix.prefix.name', true)
            ->andReturn('release/');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true)
            ->andReturn('unit_deploy');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true)
            ->andReturn('stage');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true)
            ->andReturn('master');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');

        /*
                $mock->shouldReceive('exec')
                    ->once()

                    ->with('git symbolic-ref HEAD 2>/dev/null', true)
                    ->andReturn('refs/heads/feature/example_1');
        */
        /*
                $mock->shouldReceive('exec')
                    ->once()

                    ->with('git log --pretty=fuller --name-status --left-right upstream/stage..refs/heads/feature/example_1', true)
                    ->andReturn('diff text');
        */
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false)
            ->andReturn();
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false)
            ->andReturn();

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch unit_deploy', false)
            ->andReturn();
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p unit_deploy', false)
            ->andReturn();
        $mock->shouldReceive('exec')
            ->once()
            ->with('git remote', false)
            ->andReturn('unit_deploy');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -a', false)
            ->andReturn();


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $ReleaseDriver = App::make(HotfixDriver::class);

        $res = $ReleaseDriver->isBuildOpen();

        $this->assertEquals(false, $res);

    }

    public function testGetBuildRepository()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.hotfix.prefix.name', true)
            ->andReturn('unit_release/');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true)
            ->andReturn('unit_deploy');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true)
            ->andReturn('stage');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true)
            ->andReturn('master');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');

        /*
                $mock->shouldReceive('exec')
                    ->once()

                    ->with('git symbolic-ref HEAD 2>/dev/null', true)
                    ->andReturn('refs/heads/feature/example_1');
        */
        /*
                $mock->shouldReceive('exec')
                    ->once()

                    ->with('git log --pretty=fuller --name-status --left-right upstream/stage..refs/heads/feature/example_1', true)
                    ->andReturn('diff text');
        */
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false)
            ->andReturn();
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false)
            ->andReturn();

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch unit_deploy', false)
            ->andReturn();
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p unit_deploy', false)
            ->andReturn();
        $mock->shouldReceive('exec')
            ->once()
            ->with('git remote', false)
            ->andReturn('unit_deploy');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -a', false)
            ->andReturn('remotes/unit_deploy/unit_release/123456789');


        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $ReleaseDriver = App::make(HotfixDriver::class);

        $res = $ReleaseDriver->getBuildRepository();

        $this->assertEquals('unit_release/123456789', $res);

    }
}
