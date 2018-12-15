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
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\Tester\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ConfigDriverErrorTest extends TestCase
{
    /**
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testMaster()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
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

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->master();

        $this->assertSame('master', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
        ], data_get($spy, '*.0'));

        $res = $ConfigDriver->master();

        $this->assertSame('master', $res);
        $this->assertCount(1, $spy);
    }

    /**
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testSetGlobalParameter()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
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

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->setGlobalParameter('feature', 'test');

        $this->assertNull($res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
        ], data_get($spy, '*.0'));
    }

    /**
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testDeployRemote()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
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

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->deployRemote();

        $this->assertSame('deploy', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
        ], data_get($spy, '*.0'));

        $res = $ConfigDriver->deployRemote();

        $this->assertSame('deploy', $res);
        $this->assertCount(1, $spy);
    }

    /**
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testReleasePrefix()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
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

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->releasePrefix();

        $this->assertSame('release/', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
        ], data_get($spy, '*.0'));

        $res = $ConfigDriver->releasePrefix();

        $this->assertSame('release/', $res);
        $this->assertCount(1, $spy);
    }

    /**
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testSetLocalParameter()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
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

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->setLocalParameter('feature', 'test');

        $this->assertNull($res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
        ], data_get($spy, '*.0'));
    }

    /**
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testSetSystemParameter()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
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

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->setSystemParameter('feature', 'test');

        $this->assertNull($res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
        ], data_get($spy, '*.0'));
    }

    /**
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testGetGitLiveParameter()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
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

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->getGitLiveParameter('feature');

        $this->assertNull($res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
        ], data_get($spy, '*.0'));
    }

    /**
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeaturePrefix()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->twice()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
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

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->featurePrefix();

        $this->assertSame('feature/', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git rev-parse --git-dir 2> /dev/null",
        ], data_get($spy, '*.0'));

        $res = $ConfigDriver->featurePrefix();

        $this->assertSame('feature/', $res);
        $this->assertCount(2, $spy);
    }

    /**
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testHotfixPrefix()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
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

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->hotfixPrefix();

        $this->assertSame('hotfix/', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
        ], data_get($spy, '*.0'));

        $res = $ConfigDriver->hotfixPrefix();

        $this->assertSame('hotfix/', $res);
        $this->assertCount(1, $spy);
    }

    /**
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testDevelop()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
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

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->develop();

        $this->assertSame('develop', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
        ], data_get($spy, '*.0'));

        $res = $ConfigDriver->develop();

        $this->assertSame('develop', $res);
        $this->assertCount(1, $spy);
    }
}
