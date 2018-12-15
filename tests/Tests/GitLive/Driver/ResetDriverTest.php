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

namespace Tests\GitLive\Driver;

use App;
use GitLive\Application\Container;
use GitLive\Driver\Exception;
use GitLive\Driver\ResetDriver;
use GitLive\GitLive;
use GitLive\Mock\SystemCommand;
use GitLive\Support\GitCmdExecutor;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\Tester\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ResetDriverTest extends TestCase
{
    /**
     * @throws Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\ResetDriver
     */
    public function testUpstream()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->never()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --abbrev-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'feature/unit_testing';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git status', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'On branch master
nothing to commit, working tree clean';
            });

        $mock->shouldReceive('exec')
            //->once()
            //->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $ResetDriver = App::make(ResetDriver::class);

        $ResetDriver->upstream();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git status",
            1 => "git fetch upstream",
            2 => "git fetch -p upstream",
            3 => "git rev-parse --abbrev-ref HEAD 2>/dev/null",
            4 => "git reset --hard upstream/feature/unit_testing",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\ResetDriver
     */
    public function testForcePullOrigin()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            //->once()
            //->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $SystemCommand = App::make(SystemCommandInterface::class);
        $GitCmdExecutor = App::make(GitCmdExecutor::class);
        $GitLive = App::make(GitLive::class);

        $ResetDriver = \Mockery::mock(
            ResetDriver::class . '[origin,upstream,deploy]',
            [$GitLive, $GitCmdExecutor, $SystemCommand]
        );

        $ResetDriver->shouldReceive('origin')
            ->once()
            ->andReturn('');
        $ResetDriver->shouldReceive('upstream')
            ->never()
            ->andReturn('');
        $ResetDriver->shouldReceive('deploy')
            ->never()
            ->andReturn('');

        /**
         * @var ResetDriver $ResetDriver
         */
        $ResetDriver->forcePull('origin');

        dump(data_get($spy, '*.0'));
        $this->assertSame([
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\ResetDriver
     */
    public function testForcePullUpstream()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            //->once()
            //->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $SystemCommand = App::make(SystemCommandInterface::class);
        $GitCmdExecutor = App::make(GitCmdExecutor::class);
        $GitLive = App::make(GitLive::class);

        $ResetDriver = \Mockery::mock(
            ResetDriver::class . '[origin,upstream,deploy]',
            [$GitLive, $GitCmdExecutor, $SystemCommand]
        );

        $ResetDriver->shouldReceive('origin')
            ->never()
            ->andReturn('');
        $ResetDriver->shouldReceive('upstream')
            ->once()
            ->andReturn('');
        $ResetDriver->shouldReceive('deploy')
            ->never()
            ->andReturn('');

        /**
         * @var ResetDriver $ResetDriver
         */
        $ResetDriver->forcePull('upstream');

        dump(data_get($spy, '*.0'));
        $this->assertSame([
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\ResetDriver
     */
    public function testForcePullDeploy()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            //->once()
            //->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $SystemCommand = App::make(SystemCommandInterface::class);
        $GitCmdExecutor = App::make(GitCmdExecutor::class);
        $GitLive = App::make(GitLive::class);

        $ResetDriver = \Mockery::mock(
            ResetDriver::class . '[origin,upstream,deploy]',
            [$GitLive, $GitCmdExecutor, $SystemCommand]
        );

        $ResetDriver->shouldReceive('origin')
            ->never()
            ->andReturn('');
        $ResetDriver->shouldReceive('upstream')
            ->never()
            ->andReturn('');
        $ResetDriver->shouldReceive('deploy')
            ->once()
            ->andReturn('');

        /**
         * @var ResetDriver $ResetDriver
         */
        $ResetDriver->forcePull('deploy');

        dump(data_get($spy, '*.0'));
        $this->assertSame([
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\ResetDriver
     * @expectedException Exception
     */
    public function testForcePullError()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            //->once()
            //->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $SystemCommand = App::make(SystemCommandInterface::class);
        $GitCmdExecutor = App::make(GitCmdExecutor::class);
        $GitLive = App::make(GitLive::class);

        $ResetDriver = \Mockery::mock(
            ResetDriver::class . '[origin,upstream,deploy]',
            [$GitLive, $GitCmdExecutor, $SystemCommand]
        );

        $ResetDriver->shouldReceive('origin')
            ->never()
            ->andReturn('');
        $ResetDriver->shouldReceive('upstream')
            ->never()
            ->andReturn('');
        $ResetDriver->shouldReceive('deploy')
            ->never()
            ->andReturn('');

        /**
         * @var ResetDriver $ResetDriver
         */
        $ResetDriver->forcePull('aaaa');

        dump(data_get($spy, '*.0'));
        $this->assertSame([
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\ResetDriver
     */
    public function testOrigin()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->never()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --abbrev-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'feature/unit_testing';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git status', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'On branch master
nothing to commit, working tree clean';
            });

        $mock->shouldReceive('exec')
            //->once()
            //->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $ResetDriver = App::make(ResetDriver::class);

        $ResetDriver->origin();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git status",
            1 => "git fetch origin",
            2 => "git fetch -p origin",
            3 => "git rev-parse --abbrev-ref HEAD 2>/dev/null",
            4 => "git reset --hard origin/feature/unit_testing",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\ResetDriver
     */
    public function testDeploy()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --abbrev-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'feature/unit_testing';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git status', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'On branch master
nothing to commit, working tree clean';
            });

        $mock->shouldReceive('exec')
            //->once()
            //->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $ResetDriver = App::make(ResetDriver::class);

        $ResetDriver->deploy();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git status",
            1 => "git rev-parse --git-dir 2> /dev/null",
            2 => "git config --get gitlive.deploy.remote",
            3 => "git fetch deploy",
            4 => "git fetch -p deploy",
            5 => "git rev-parse --abbrev-ref HEAD 2>/dev/null",
            6 => "git reset --hard deploy/feature/unit_testing",
        ], data_get($spy, '*.0'));
    }
}
