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

use GitLive\Application\Container;
use GitLive\Application\Facade as App;
use GitLive\Driver\FetchDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\Tester\TestCase;

/**
 * Class FetchDriverTest
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
 * @since      2018-12-16
 * @internal
 * @coversNothing
 */
class FetchDriverTest extends TestCase
{
    /**
     * @covers \GitLive\Driver\FetchDriver
     */
    public function testAll()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        /*
        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '.git';
            });
        */
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
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

        $FetchDriver = App::make(FetchDriver::class);

        $FetchDriver->all();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            'git fetch --all',
            'git fetch -p',
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @covers \GitLive\Driver\FetchDriver
     */
    public function testDeploy()
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

                return 'build';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch build', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p build', false, null)
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

        $FetchDriver = App::make(FetchDriver::class);

        $FetchDriver->deploy();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            'git rev-parse --git-dir 2> /dev/null',
            'git config --get gitlive.deploy.remote',
            'git fetch build',
            'git fetch -p build',
        ], data_get($spy, '*.0'));
    }

    /**
     * @covers \GitLive\Driver\FetchDriver
     */
    public function testUpstream()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        /*
        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '.git';
            });
        */
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
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

        $FetchDriver = App::make(FetchDriver::class);

        $FetchDriver->upstream();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            'git fetch upstream',
            'git fetch -p upstream',
        ], data_get($spy, '*.0'));
    }

    /**
     * @covers \GitLive\Driver\FetchDriver
     */
    public function testOrigin()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        /*
        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '.git';
            });
        */
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch origin', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p origin', false, null)
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

        $FetchDriver = App::make(FetchDriver::class);

        $FetchDriver->origin();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            'git fetch origin',
            'git fetch -p origin',
        ], data_get($spy, '*.0'));
    }
}
