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
use GitLive\Driver\PullRequestDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\TestCase;

/**
 * @internal
 * @coversNothing
 */
class PullRequestDriverTest extends TestCase
{
    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\PullRequestDriver
     */
    public function testPrTrack()
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
            //->once()
            //->with('git config --global gitlive.global_param_key "global_param_value"', true, null)
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

        $PullRequestDriver = App::make(PullRequestDriver::class);

        $PullRequestDriver->prTrack(24);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git fetch --all",
            1 => "git fetch -p",
            2 => "git fetch upstream",
            3 => "git fetch -p upstream",
            4 => "git fetch upstream '+refs/pull/*:refs/remotes/pr/*'",
            5 => "git checkout -b pullreq/24 remotes/pr/24/head",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\PullRequestDriver
     */
    public function testPrPull()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            //->never()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'refs/heads/pullreq/24';
            });
        $mock->shouldReceive('exec')
            //->once()
            //->with('git config --global gitlive.global_param_key "global_param_value"', true, null)
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

        $PullRequestDriver = App::make(PullRequestDriver::class);

        $PullRequestDriver->prPull();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git symbolic-ref HEAD 2>/dev/null",
            1 => "git fetch --all",
            2 => "git fetch -p",
            3 => "git fetch upstream",
            4 => "git fetch -p upstream",
            5 => "git fetch upstream '+refs/pull/*:refs/remotes/pr/*'",
            6 => "git pull upstream pull/24/head",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\PullRequestDriver
     */
    public function testPrPullNotPrRepo()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            //->never()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'refs/heads/feature/pullreq/24';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $PullRequestDriver = App::make(PullRequestDriver::class);

        $PullRequestDriver->prPull();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git symbolic-ref HEAD 2>/dev/null",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\PullRequestDriver
     */
    public function testFeatureStartSoft()
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
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'featurePrefix/';
            });

        $mock->shouldReceive('exec')
            //->once()
            //->with('git config --global gitlive.global_param_key "global_param_value"', true, null)
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

        $PullRequestDriver = App::make(PullRequestDriver::class);

        $PullRequestDriver->featureStartSoft(24, 'new_feature_name');

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git fetch --all",
            1 => "git fetch -p",
            2 => "git fetch upstream",
            3 => "git fetch -p upstream",
            4 => "git fetch upstream '+refs/pull/*:refs/remotes/pr/*'",
            5 => "git rev-parse --git-dir 2> /dev/null",
            6 => "git config --get gitlive.branch.feature.prefix.ignore",
            7 => "git rev-parse --git-dir 2> /dev/null",
            8 => "git config --get gitlive.branch.feature.prefix.name",
            9 => "git branch -a",
            10 => "git checkout remotes/pr/24/head",
            11 => "git checkout -b featurePrefix/new_feature_name remotes/pr/24/head",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\PullRequestDriver
     */
    public function testPrMerge()
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
            //->once()
            //->with('git config --global gitlive.global_param_key "global_param_value"', true, null)
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

        $PullRequestDriver = App::make(PullRequestDriver::class);

        $PullRequestDriver->prMerge(24);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git fetch --all",
            1 => "git fetch -p",
            2 => "git fetch upstream",
            3 => "git fetch -p upstream",
            4 => "git fetch upstream '+refs/pull/*:refs/remotes/pr/*'",
            5 => "git pull upstream pull/24/head",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @throws \GitLive\Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\PullRequestDriver
     */
    public function testFeatureStart()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            //->never()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'refs/heads/feature/example_1';
            });

        $mock->shouldReceive('exec')
            //->once()
            //->with('git config --global gitlive.global_param_key "global_param_value"', true, null)
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

        $PullRequestDriver = App::make(PullRequestDriver::class);

        $PullRequestDriver->featureStart(24, 'new_feature_name');

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git fetch --all",
            1 => "git fetch -p",
            2 => "git fetch upstream",
            3 => "git fetch -p upstream",
            4 => "git fetch upstream '+refs/pull/*:refs/remotes/pr/*'",
            5 => "git rev-parse --git-dir 2> /dev/null",
            6 => "git config --get gitlive.branch.feature.prefix.ignore",
            7 => "git rev-parse --git-dir 2> /dev/null",
            8 => "git config --get gitlive.branch.feature.prefix.name",
            9 => "git branch -a",
            10 => "git checkout upstream/develop",
            11 => "git checkout -b feature/new_feature_name",
            12 => "git symbolic-ref HEAD 2>/dev/null",
            13 => "git pull upstream pull/24/head",
        ], data_get($spy, '*.0'));
    }
}
