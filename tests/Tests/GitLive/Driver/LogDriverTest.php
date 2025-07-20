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

use GitLive\Application\Facade as App;
use GitLive\Application\Container;
use GitLive\Driver\LogDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\Tester\TestCase;

/**
 * Class LogDriverTest
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
class LogDriverTest extends TestCase
{
    /**
     * @throws \GitLive\Driver\Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\LogDriver
     */
    public function testLogDevelop()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
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

                return 'stage';
            });

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

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --abbrev-ref HEAD 2> /dev/null', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'refs/heads/feature/example_1';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git log --left-right upstream/stage..refs/heads/feature/example_1', false, 256)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'diff text';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $logDriver = App::make(LogDriver::class);

        $res = $logDriver->logDevelop();

        $this->assertSame('diff text', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            'git rev-parse --git-dir 2> /dev/null',
            'git config --get gitlive.branch.develop.name',
            'git fetch --all',
            'git fetch -p',
            'git rev-parse --abbrev-ref HEAD 2> /dev/null',
            'git log --left-right upstream/stage..refs/heads/feature/example_1',
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\LogDriver
     */
    public function testLogMaster()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
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

                return '';
            });

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

        $mock->shouldReceive('exec')
            ->once()
            ->with('git rev-parse --abbrev-ref HEAD 2> /dev/null', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'refs/heads/feature/example_1';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git log --left-right upstream/master..refs/heads/feature/example_1', false, 256)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'diff text';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $logDriver = App::make(LogDriver::class);

        $res = $logDriver->logMaster();

        $this->assertSame('diff text', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            'git rev-parse --git-dir 2> /dev/null',
            'git config --get gitlive.branch.master.name',
            'git fetch --all',
            'git fetch -p',
            'git rev-parse --abbrev-ref HEAD 2> /dev/null',
            'git log --left-right upstream/master..refs/heads/feature/example_1',
        ], data_get($spy, '*.0'));
    }
}
