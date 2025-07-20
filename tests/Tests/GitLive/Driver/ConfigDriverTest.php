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

namespace tests\GitLive\Driver;

use GitLive\Application\Facade as App;
use GitLive\Application\Container;
use GitLive\Driver\ConfigDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\Tester\TestCase;

/**
 * Class ConfigDriverTest
 *
 * @category   GitCommand
 * @package    ests\GitLive\Driver
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-16
 * @internal
 * @coversNothing
 */
class ConfigDriverTest extends TestCase
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
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'test_config_data';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->master();

        $this->assertSame('test_config_data', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.master.name',
        ], data_get($spy, '*.0'));

        $res = $ConfigDriver->master();

        $this->assertSame('test_config_data', $res);
        $this->assertCount(2, $spy);
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
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --global gitlive.global_param_key "global_param_value"', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->setGlobalParameter('global_param_key', 'global_param_value');

        $this->assertSame('', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --global gitlive.global_param_key "global_param_value"',
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
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'deploy_remote_data';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->deployRemote();

        $this->assertSame('deploy_remote_data', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.deploy.remote',
        ], data_get($spy, '*.0'));

        $res = $ConfigDriver->deployRemote();

        $this->assertSame('deploy_remote_data', $res);
        $this->assertCount(2, $spy);
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
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'release_prefix_data';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->releasePrefix();

        $this->assertSame('release_prefix_data', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.release.prefix.name',
        ], data_get($spy, '*.0'));

        $res = $ConfigDriver->releasePrefix();

        $this->assertSame('release_prefix_data', $res);

        $this->assertCount(2, $spy);
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
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --local gitlive.local_param_key "local_param_value"', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->setLocalParameter('local_param_key', 'local_param_value');

        $this->assertSame('', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --local gitlive.local_param_key "local_param_value"',
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
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --system gitlive.system_param_key "system_param_value"', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->setSystemParameter('system_param_key', 'system_param_value');

        $this->assertSame('', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --system gitlive.system_param_key "system_param_value"',
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
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'config_data_value';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->getGitLiveParameter('branch.master.name');

        $this->assertSame('config_data_value', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.master.name',
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
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'config_data_value';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->featurePrefix();

        $this->assertSame('config_data_value', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.feature.prefix.ignore',
            2 => 'git rev-parse --git-dir 2> /dev/null',
            3 => 'git config --get gitlive.branch.feature.prefix.name',
        ], data_get($spy, '*.0'));

        $res = $ConfigDriver->featurePrefix();

        $this->assertSame('config_data_value', $res);
        $this->assertCount(4, $spy);
    }

    /**
     * @covers \GitLive\Driver\ConfigDriver
     * @covers \GitLive\Driver\DriverBase
     */
    public function testFeaturePrefixIgnore()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'true';
            });
        $mock->shouldReceive('exec')
            ->never()
            ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'config_data_value';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->featurePrefix();

        $this->assertSame('', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.feature.prefix.ignore',
        ], data_get($spy, '*.0'));

        $res = $ConfigDriver->featurePrefix();

        $this->assertSame('', $res);
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
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.hotfix.prefix.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'config_data_value';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->hotfixPrefix();

        $this->assertSame('config_data_value', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.hotfix.prefix.name',
        ], data_get($spy, '*.0'));

        $res = $ConfigDriver->hotfixPrefix();

        $this->assertSame('config_data_value', $res);
        $this->assertCount(2, $spy);
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
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'config_data_value';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $ConfigDriver = App::make(ConfigDriver::class);

        $res = $ConfigDriver->develop();

        $this->assertSame('config_data_value', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.branch.develop.name',
        ], data_get($spy, '*.0'));

        $res = $ConfigDriver->develop();

        $this->assertSame('config_data_value', $res);
        $this->assertCount(2, $spy);
    }
}
