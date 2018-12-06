<?php
/**
 * InitDriverTest.php
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
 * @since      2018-12-05
 */

namespace Tests\GitLive\Driver;

use App;
use GitLive\Application\Container;
use GitLive\Driver\Exception;
use GitLive\Driver\InitDriver;
use GitLive\Driver\ReleaseDriver;
use GitLive\Mock\InteractiveShell;
use GitLive\Mock\SystemCommand;
use GitLive\Support\InteractiveShellInterface;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\TestCase;

class InitDriverTest extends TestCase
{

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\InitDriver
     * @covers \GitLive\Driver\DriverBase
     * @expectedException Exception
     */
    public function testRestart_error()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            //->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturn('build');

        $mock->shouldReceive('exec')
            //->once()
            ->with('git remote -v', 256, NULL)
            ->andReturn('');



        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $InitDriver = App::make(InitDriver::class);

        $InitDriver->restart();

        $this->assertTrue(true);

    }
    public function testRestart()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git reset --hard HEAD', false, NULL)
            ->andReturn('build');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git clean -df', false, NULL)
            ->andReturn('build');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git remote -v', 256, NULL)
            ->andReturn('build	https://github.com/Git-Live/TestRepository.git (fetch)
build	https://github.com/Git-Live/TestRepository.git (push)
origin	https://github.com/suzunone/TestRepository.git (fetch)
origin	https://github.com/suzunone/TestRepository.git (push)
upstream	https://github.com/Git-Live/TestRepository.git (fetch)
upstream	https://github.com/Git-Live/TestRepository.git (push)');



        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturn('build');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, NULL)
            ->andReturn('staging');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, NULL)
            ->andReturn('v2.0');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p build', false, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout -b temp', false, NULL)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -d staging', false, NULL)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -d v2.0', false, NULL)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin :staging', false, NULL)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin :v2.0', false, NULL)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout remotes/upstream/staging', false, NULL)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout -b staging', false, NULL)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin staging', false, NULL)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout remotes/upstream/v2.0', false, NULL)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout -b v2.0', false, NULL)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin v2.0', false, NULL)
            ->andReturn('');


        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('');


        $shell_mock = \Mockery::mock(InteractiveShell::class);
        $shell_mock->shouldReceive('interactiveShell')
            ->once()
            ->with('Rebuild? yes/no', false)
            ->andReturn('yes');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );


        Container::bind(
            InteractiveShellInterface::class,
            function () use ($shell_mock) {
                return $shell_mock;
            }
        );

        $InitDriver = App::make(InitDriver::class);

        $InitDriver->restart();

        $this->assertTrue(true);

    }

    public function testStart()
    {

    }
}
