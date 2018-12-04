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
use GitLive\Driver\ConfigDriver;
use GitLive\Driver\FeatureDriver;
use GitLive\GitLive;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\TestCase;

/**
 * Class FeatureDriverTest
 *
 * @category   GitCommand
 * @package Tests\GitLive\Driver
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      2018/11/25
 *
 * @internal
 * @coversNothing
 */
class FeatureDriverTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeatureTrack()
    {
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
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
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --abbrev-ref HEAD 2>/dev/null', true, null)
            ->andReturn('feature/unit_testing');

        $mock->shouldReceive('exec')
            ->never()
            ->with('git checkout upstream/feature/unit_testing', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->never()
            ->with('git checkout -b feature/unit_testing', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git pull upstream feature/unit_testing', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featureTrack('feature/unit_testing');

        $this->assertTrue(true);
    }

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeatureTrackOther()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
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
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --abbrev-ref HEAD 2>/dev/null', true, null)
            ->andReturn('feature/unit_testing_other');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout upstream/feature/unit_testing', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout -b feature/unit_testing', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git pull upstream feature/unit_testing', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featureTrack('feature/unit_testing');

        $this->assertTrue(true);
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeatureStart()
    {
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturn('staging');

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
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');
        /*
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
            ->andReturn('refs/heads/feature/example_1');
        */
        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout upstream/staging', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout -b feature/unit_testing', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featureStart('unit_testing');

        $this->assertTrue(true);
    }

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeaturePublish()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
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
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
            ->andReturn('refs/heads/feature/example_1');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push upstream refs/heads/feature/example_1', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePublish();

        $this->assertTrue(true);
    }

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeaturePublishFeatureignore()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturn('true');

        $mock->shouldReceive('exec')
            ->never()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
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
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
            ->andReturn('refs/heads/example_1');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push upstream refs/heads/example_1', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePublish();

        $this->assertTrue(true);
    }

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeaturePushNooption()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
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
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
            ->andReturn('refs/heads/feature/example_1');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin refs/heads/feature/example_1', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePush();

        $this->assertTrue(true);
    }

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeaturePushWithoption1()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
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
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin feature/unit_test/example_2', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePush('unit_test/example_2');

        $this->assertTrue(true);
    }

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeaturePushWithoption2()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
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
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin feature/example_3', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePush('feature/example_3');

        $this->assertTrue(true);
    }

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeaturePushWithoption3()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturn('true');

        $mock->shouldReceive('exec')
            ->never()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
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
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin example_3', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePush('example_3');

        $this->assertTrue(true);
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeatureList()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->with('git branch --list "feature/*"', true, null)
            ->andReturn('feature/hogehoge');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $res = $FeatureDriver->featureList();

        $this->assertSame('feature/hogehoge', $res);
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeatureChange()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->with('git checkout feature/unit_test_2', false, null)
            ->andReturn('feature/hogehoge');

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

        $FeatureDriver = App::make(FeatureDriver::class);

        $res = $FeatureDriver->featureChange('unit_test_2');

        $this->assertTrue(true);
    }

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\FeatureDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeaturePull()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
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
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
            ->andReturn('refs/heads/feature/example_1');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git pull upstream refs/heads/feature/example_1', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featurePull();

        $this->assertTrue(true);
    }
}
