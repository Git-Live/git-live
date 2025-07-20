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
use GitLive\Driver\MergeDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\Tester\TestCase;

/**
 * Class MergeDriverTest
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
class MergeDriverTest extends TestCase
{
    /**
     * @throws \GitLive\Driver\Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\MergeDriver
     */
    public function testStateDevelop()
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
        /*
                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
                    ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '';
            });

                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
                    ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '';
            });
        */

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/stage --stdout', 256, 256)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        /*
        $mock->shouldReceive('exec')
            ->once()
            ->with('git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/stage --stdout| git apply --check', 256, 256)
            ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '';
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

        $MergeDriver = App::make(MergeDriver::class);

        $MergeDriver->stateDevelop();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            'git rev-parse --git-dir 2> /dev/null',
            'git config --get gitlive.branch.develop.name',
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/stage --stdout',
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\MergeDriver
     */
    public function testStateMaster()
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
        /*
                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
                    ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '';
            });

                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
                    ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '';
            });
        */

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/master --stdout', 256, 256)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'error: test.file: No such file or directory';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/master --stdout| git apply --check', false, 256)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'error: test.file: No such file or directory';
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

        $MergeDriver = App::make(MergeDriver::class);

        $res = $MergeDriver->stateMaster();

        $this->assertSame('error: test.file: No such file or directory', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            'git rev-parse --git-dir 2> /dev/null',
            'git config --get gitlive.branch.master.name',
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/master --stdout',
            'git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/master --stdout| git apply --check',
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\MergeDriver
     */
    public function testMergeDevelop()
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
        /*
                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
                    ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '';
            });

                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
                    ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '';
            });
        */

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

        $mock->shouldReceive('exec')
            ->once()
            ->with('git merge upstream/stage', false, null)
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

        $MergeDriver = App::make(MergeDriver::class);

        $MergeDriver->mergeDevelop();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            'git rev-parse --git-dir 2> /dev/null',
            'git config --get gitlive.branch.develop.name',
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git merge upstream/stage',
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\MergeDriver
     */
    public function testMergeMaster()
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
        /*
                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
                    ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '';
            });

                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
                    ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return '';
            });
        */

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
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

        $mock->shouldReceive('exec')
            ->once()
            ->with('git merge upstream/master', false, null)
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

        $MergeDriver = App::make(MergeDriver::class);

        $MergeDriver->mergeMaster();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            'git rev-parse --git-dir 2> /dev/null',
            'git config --get gitlive.branch.master.name',
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git merge upstream/master',
        ], data_get($spy, '*.0'));
    }
}
