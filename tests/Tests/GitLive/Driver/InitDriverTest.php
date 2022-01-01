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
            ->between(4, 4)
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
            0 => "git remote -v",
            1 => "git rev-parse --git-dir 2> /dev/null",
            2 => "git config --get gitlive.deploy.remote",
            3 => "git reset --hard HEAD",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git clean -df",
            6 => "git fetch --all",
            7 => "git fetch -p",
            8 => "git checkout -b temp",
            9 => "git rev-parse --git-dir 2> /dev/null",
            10 => "git config --get gitlive.branch.develop.name",
            11 => "git branch -d staging",
            12 => "git rev-parse --git-dir 2> /dev/null",
            13 => "git config --get gitlive.branch.master.name",
            14 => "git branch -d v2.0",
            15 => "git push origin :staging",
            16 => "git push origin :v2.0",
            17 => "git checkout remotes/upstream/staging",
            18 => "git checkout -b staging",
            19 => "git push origin staging",
            20 => "git checkout remotes/upstream/v2.0",
            21 => "git checkout -b v2.0",
            22 => "git push origin v2.0",
            23 => "git fetch --all",
            24 => "git fetch -p",
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
            0 => "git stash -u",
            1 => "git reset --hard HEAD",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git clean -df",
            4 => "git fetch --all",
            5 => "git fetch -p",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.develop.name",
            8 => "git checkout develop",
            9 => "git pull upstream develop",
            10 => "git push origin develop",
            11 => "git rev-parse --git-dir 2> /dev/null",
            12 => "git config --get gitlive.branch.master.name",
            13 => "git checkout master",
            14 => "git pull upstream master",
            15 => "git push origin master",
            16 => "git pull upstream --tags",
            17 => "git push origin --tags",
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
            0 => "git stash -u",
            1 => "git reset --hard HEAD",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git clean -df",
            4 => "git fetch --all",
            5 => "git fetch -p",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.develop.name",
            8 => "git checkout develop",
            9 => "git pull upstream develop",
            10 => "git rev-parse --git-dir 2> /dev/null",
            11 => "git config --get gitlive.branch.master.name",
            12 => "git checkout master",
            13 => "git pull upstream master",
            14 => "git pull upstream --tags",
        ], data_get($spy, '*.0'));
    }
}
