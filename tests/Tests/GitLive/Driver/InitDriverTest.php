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
use GitLive\Driver\InitDriver;
use GitLive\Mock\InteractiveShell;
use GitLive\Mock\SystemCommand;
use GitLive\Support\InteractiveShellInterface;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\Tester\TestCase;

/**
 * Class InitDriverTest
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
class InitDriverTest extends TestCase
{
    /**
     * @throws Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\InitDriver
     * @expectedException Exception
     */
    public function testRestartError()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            //->once()
            ->with('git remote -v', 256, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $shell_mock = \Mockery::mock(InteractiveShell::class);

        $shell_mock->shouldReceive('interactiveShell')
            ->once()
            ->with('Rebuild? yes/no', false)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                return 'yes';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        Container::bind(
            InteractiveShellInterface::class,
            static function () use ($shell_mock) {
                return $shell_mock;
            }
        );

        $InitDriver = App::make(InitDriver::class);

        $InitDriver->restart();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\InitDriver
     */
    public function testRestart()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            ->between(3, 3)
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git reset --hard HEAD', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git clean -df', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git remote -v', 256, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'build	https://github.com/Git-Live/TestRepository.git (fetch)
build	https://github.com/Git-Live/TestRepository.git (push)
origin	https://github.com/suzunone/TestRepository.git (fetch)
origin	https://github.com/suzunone/TestRepository.git (push)
upstream	https://github.com/Git-Live/TestRepository.git (fetch)
upstream	https://github.com/Git-Live/TestRepository.git (push)';
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
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'staging';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'v2.0';
            });
        /*
                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git fetch -p build', false, null)
                   ->andReturnUsing(function(...$val) use (&$spy) {
                        $spy[] = $val;
                        return '';
                    });
        */
        $mock->shouldReceive('exec')
            ->twice()
            ->with('git fetch --all', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->twice()
            ->with('git fetch -p', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout -b temp', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -d staging', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -d v2.0', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin :staging', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin :v2.0', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout remotes/upstream/staging', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout -b staging', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin staging', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout remotes/upstream/v2.0', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git checkout -b v2.0', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin v2.0', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->with('git push origin v2.0', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });

        /*
        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -d develop', false, NULL)
           ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '';
            });


        $mock->shouldReceive('exec')
            ->once()
            ->with('git push origin :develop', false, NULL)
           ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '';
            });
*/
        $shell_mock = \Mockery::mock(InteractiveShell::class);
        $shell_mock->shouldReceive('interactiveShell')
            ->once()
            ->with('Rebuild? yes/no', false)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                return 'yes';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        Container::bind(
            InteractiveShellInterface::class,
            static function () use ($shell_mock) {
                return $shell_mock;
            }
        );

        $InitDriver = App::make(InitDriver::class);

        $InitDriver->restart();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            'git remote -v',
            'git rev-parse --git-dir 2> /dev/null',
            'git config --get gitlive.deploy.remote',
            'git reset --hard HEAD',
            'git clean -df',
            'git fetch --all',
            'git fetch -p',
            'git checkout -b temp',
            'git rev-parse --git-dir 2> /dev/null',
            'git config --get gitlive.branch.develop.name',
            'git branch -d staging',
            'git rev-parse --git-dir 2> /dev/null',
            'git config --get gitlive.branch.master.name',
            'git branch -d v2.0',
            'git push origin :staging',
            'git push origin :v2.0',
            'git checkout remotes/upstream/staging',
            'git checkout -b staging',
            'git push origin staging',
            'git checkout remotes/upstream/v2.0',
            'git checkout -b v2.0',
            'git push origin v2.0',
            'git fetch --all',
            'git fetch -p',
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\InitDriver
     */
    public function testRestartNot()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);

        $mock->shouldReceive('exec')
            ->never()
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        $shell_mock = \Mockery::mock(InteractiveShell::class);

        $shell_mock->shouldReceive('interactiveShell')
            ->once()
            ->with('Rebuild? yes/no', false)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                return 'aaaa';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        Container::bind(
            InteractiveShellInterface::class,
            static function () use ($shell_mock) {
                return $shell_mock;
            }
        );

        $InitDriver = App::make(InitDriver::class);

        $InitDriver->restart();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
        ], data_get($spy, '*.0'));
    }

    public function testStart()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            //->never()
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

        $InitDriver = App::make(InitDriver::class);

        $InitDriver->start(false);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git stash -u',
            1 => 'git reset --hard HEAD',
            2 => 'git clean -df',
            3 => 'git fetch --all',
            4 => 'git fetch -p',
            5 => 'git rev-parse --git-dir 2> /dev/null',
            6 => 'git config --get gitlive.branch.develop.name',
            7 => 'git checkout develop',
            8 => 'git pull upstream develop',
            9 => 'git push origin develop',
            10 => 'git rev-parse --git-dir 2> /dev/null',
            11 => 'git config --get gitlive.branch.master.name',
            12 => 'git checkout master',
            13 => 'git pull upstream master',
            14 => 'git push origin master',
            15 => 'git pull upstream --tags',
            16 => 'git push origin --tags',
        ], data_get($spy, '*.0'));
    }

    public function testStartWoPush()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            //->never()
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

        $InitDriver = App::make(InitDriver::class);

        $InitDriver->start();

        dump(data_get($spy, '*.0'));

        $this->assertFalse(in_array('git push origin --tags', data_get($spy, '*.0'), true));
        $this->assertFalse(in_array('git push origin master', data_get($spy, '*.0'), true));
        $this->assertFalse(in_array('git push origin develop', data_get($spy, '*.0'), true));

        $this->assertFalse(in_array('git push upstream --tags', data_get($spy, '*.0'), true));
        $this->assertFalse(in_array('git push upstream master', data_get($spy, '*.0'), true));
        $this->assertFalse(in_array('git push upstream develop', data_get($spy, '*.0'), true));

        $this->assertFalse(in_array('git push deploy --tags', data_get($spy, '*.0'), true));
        $this->assertFalse(in_array('git push deploy master', data_get($spy, '*.0'), true));
        $this->assertFalse(in_array('git push deploy develop', data_get($spy, '*.0'), true));

        $this->assertSame([
            0 => 'git stash -u',
            1 => 'git reset --hard HEAD',
            2 => 'git clean -df',
            3 => 'git fetch --all',
            4 => 'git fetch -p',
            5 => 'git rev-parse --git-dir 2> /dev/null',
            6 => 'git config --get gitlive.branch.develop.name',
            7 => 'git checkout develop',
            8 => 'git pull upstream develop',
            9 => 'git rev-parse --git-dir 2> /dev/null',
            10 => 'git config --get gitlive.branch.master.name',
            11 => 'git checkout master',
            12 => 'git pull upstream master',
            13 => 'git pull upstream --tags',
        ], data_get($spy, '*.0'));
    }
}
