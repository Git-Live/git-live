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
use GitLive\Driver\FeatureDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\Tester\TestCase;

/**
 * Class FeatureDriverTest
 *
 * @category   GitCommand
 * @package    Tests\GitLive\Driver
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018/11/25
 *
 * @internal
 * @coversNothing
 */
class FeatureDriverTest extends TestCase
{
    /**
     * @throws \Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\FeatureDriver
     */
    public function testFeatureTrack()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            //->once()
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

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --abbrev-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'feature/unit_testing';
            });

        $mock->shouldReceive('exec')
            ->never()
            ->with('git checkout upstream/feature/unit_testing', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->never()
            ->with('git checkout -b feature/unit_testing', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git pull upstream feature/unit_testing', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -a', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '  remotes/upstream/feature/unit_testing
  feature/unit_testing
  remotes/upstream/feature/20180116';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featureTrack('feature/unit_testing');

        $this->assertSame(
            [
                "git rev-parse --git-dir 2> /dev/null",
                "git config --get gitlive.branch.feature.prefix.ignore",
                "git rev-parse --git-dir 2> /dev/null",
                "git config --get gitlive.branch.feature.prefix.name",
                "git fetch --all",
                "git fetch -p",
                "git fetch upstream",
                "git fetch -p upstream",
                "git rev-parse --abbrev-ref HEAD 2>/dev/null",
                'git branch -a',
                'git pull upstream feature/unit_testing',
            ],
            data_get($spy, '*.0')
        );
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\FeatureDriver
     */
    public function testFeatureTrackOther()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
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

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --abbrev-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'feature/unit_testing_other';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout upstream/feature/unit_testing', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout -b feature/unit_testing', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git pull upstream feature/unit_testing', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -a', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '  remotes/upstream/feature/unit_testing
  remotes/upstream/feature/20180115
  remotes/upstream/feature/20180116';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featureTrack('feature/unit_testing');

//        dd(data_get($spy, '*.0'));
        $this->assertSame([
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.ignore",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.name",
            "git fetch --all",
            "git fetch -p",
            "git fetch upstream",
            "git fetch -p upstream",
            "git rev-parse --abbrev-ref HEAD 2>/dev/null",
            'git branch -a',
            "git checkout upstream/feature/unit_testing",
            "git checkout -b feature/unit_testing",
            "git pull upstream feature/unit_testing",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \GitLive\Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\FeatureDriver
     */
    public function testFeatureStart()
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

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'staging';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        /*
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return 'refs/heads/feature/example_1';
            });
        */
        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout upstream/staging', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout -b feature/unit_testing', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -a', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '  remotes/upstream/feature/20171204_console
  remotes/upstream/feature/20180115
  remotes/upstream/feature/20180116';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featureStart('unit_testing');

        //dd(data_get($spy, '*.0'));
        $this->assertSame([
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
            11 => 'git checkout upstream/staging',
            12 => 'git checkout -b feature/unit_testing',
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\FeatureDriver
     */
    public function testFeaturePublish()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
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

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'refs/heads/feature/example_1';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push upstream refs/heads/feature/example_1', false, null)
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

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePublish();

        //dd(data_get($spy, '*.0'));
        $this->assertSame([
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.ignore",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.name",
            "git fetch --all",
            "git fetch -p",
            "git fetch upstream",
            "git fetch -p upstream",
            "git symbolic-ref HEAD 2>/dev/null",
            "git push upstream refs/heads/feature/example_1",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\FeatureDriver
     */
    public function testFeaturePublishFeatureignore()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
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

                return 'true';
            });

        $mock->shouldReceive('exec')
            ->never()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'refs/heads/example_1';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push upstream refs/heads/example_1', false, null)
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

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePublish();

        //dd(data_get($spy, '*.0'));
        $this->assertSame([
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.ignore",
            "git fetch --all",
            "git fetch -p",
            "git fetch upstream",
            "git fetch -p upstream",
            "git symbolic-ref HEAD 2>/dev/null",
            "git push upstream refs/heads/example_1",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\FeatureDriver
     */
    public function testFeaturePushNooption()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
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

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'refs/heads/feature/example_1';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin refs/heads/feature/example_1', false, null)
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

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePush();

        //dd(data_get($spy, '*.0'));
        $this->assertSame([
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.ignore",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.name",
            "git fetch --all",
            "git fetch -p",
            "git fetch upstream",
            "git fetch -p upstream",
            "git symbolic-ref HEAD 2>/dev/null",
            "git push origin refs/heads/feature/example_1",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\FeatureDriver
     */
    public function testFeaturePushWithoption1()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
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

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin feature/unit_test/example_2', false, null)
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

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePush('unit_test/example_2');

        //dd(data_get($spy, '*.0'));
        $this->assertSame([
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.ignore",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.name",
            "git fetch --all",
            "git fetch -p",
            "git fetch upstream",
            "git fetch -p upstream",
            "git push origin feature/unit_test/example_2",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\FeatureDriver
     */
    public function testFeaturePushWithoption2()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
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

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin feature/example_3', false, null)
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

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePush('feature/example_3');

        //dd(data_get($spy, '*.0'));
        $this->assertSame([
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.ignore",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.name",
            "git fetch --all",
            "git fetch -p",
            "git fetch upstream",
            "git fetch -p upstream",
            "git push origin feature/example_3",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\FeatureDriver
     */
    public function testFeaturePushWithoption3()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
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

                return 'true';
            });

        $mock->shouldReceive('exec')
            ->never()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin example_3', false, null)
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

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePush('example_3');

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.ignore",
            "git fetch --all",
            "git fetch -p",
            "git fetch upstream",
            "git fetch -p upstream",
            "git push origin example_3",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\FeatureDriver
     */
    public function testFeatureList()
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
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->with('git branch --list "feature/*"', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'feature/hogehoge';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $res = $FeatureDriver->featureList();

        $this->assertSame('feature/hogehoge', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.ignore",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.name",
            'git branch --list "feature/*"',
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\FeatureDriver
     */
    public function testFeatureChange()
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
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'staging';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'v2.0';
            });

        $mock->shouldReceive('exec')
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->with('git checkout feature/unit_test_2', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'feature/hogehoge';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -a', true, null)
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

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featureChange('unit_test_2');

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.ignore",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.name",
            "git fetch --all",
            "git fetch -p",
            "git branch -a",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.master.name",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.develop.name",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\FeatureDriver
     */
    public function testFeaturePull()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
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

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --abbrev-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'feature/example_1';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git pull upstream feature/example_1', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git pull origin feature/example_1', false, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            // ->once()
            ->with('git branch -a', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '  remotes/upstream/feature/example_1
                  remotes/origin/feature/example_1
                feature/example_1';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePull();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.ignore",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.feature.prefix.name",
            "git fetch --all",
            "git fetch -p",
            "git fetch upstream",
            "git fetch -p upstream",
            'git rev-parse --abbrev-ref HEAD 2>/dev/null',
            'git branch -a',
            'git pull upstream feature/example_1',
            'git pull origin feature/example_1',
        ], data_get($spy, '*.0'));
    }
}
