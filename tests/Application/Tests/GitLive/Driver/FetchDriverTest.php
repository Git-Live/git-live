<?php
/**
 * FetchDriverTest.php
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

use GitLive\Driver\FetchDriver;
use App;
use GitLive\Application\Container;
use GitLive\Driver\HotfixDriver;
use GitLive\Driver\ReleaseDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\TestCase;

class FetchDriverTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FetchDriver
     */
    public function testAll()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        /*
        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');
        */
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturn('');




        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FetchDriver = App::make(FetchDriver::class);

        $FetchDriver->all();

        $this->assertTrue(true);

    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FetchDriver
     */
    public function testDeploy()
    {
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturn('build');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch build', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p build', false, null)
            ->andReturn('');




        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FetchDriver = App::make(FetchDriver::class);

        $FetchDriver->deploy();

        $this->assertTrue(true);

    }

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FetchDriver
     */
    public function testUpstream()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        /*
        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');
        */
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');




        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FetchDriver = App::make(FetchDriver::class);

        $FetchDriver->upstream();

        $this->assertTrue(true);

    }

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FetchDriver
     */
    public function testOrigin()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        /*
        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');
        */
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch origin', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p origin', false, null)
            ->andReturn('');




        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FetchDriver = App::make(FetchDriver::class);

        $FetchDriver->origin();

        $this->assertTrue(true);

    }
}
