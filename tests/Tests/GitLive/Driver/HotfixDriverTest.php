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
use GitLive\Driver\HotfixDriver;
use GitLive\Driver\ReleaseDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\Tester\TestCase;

/**
 * Class HptfixDriverTest
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
class HptfixDriverTest extends TestCase
{
    /**
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\HotfixDriver
     */
    public function testIsBuildOpen()
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
            //->once()
            ->with('git config --get gitlive.branch.hotfix.prefix.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'release/';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
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
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturn('');

        /*
                $mock->shouldReceive('exec')
                    ->once()

                    ->with('git symbolic-ref HEAD 2> /dev/null', true, null)
                    ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return 'refs/heads/feature/example_1';
            });
        */
        /*
                $mock->shouldReceive('exec')
                    ->once()

                    ->with('git log --pretty=fuller --name-status --left-right upstream/stage..refs/heads/feature/example_1', true, null)
                    ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return 'diff text';
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

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch unit_deploy', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p unit_deploy', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git remote', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -a --no-color', true, null)
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

        $ReleaseDriver = App::make(HotfixDriver::class);

        $res = $ReleaseDriver->isBuildOpen();

        $this->assertSame(false, $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.deploy.remote',
            2 => 'git rev-parse --git-dir 2> /dev/null',
            3 => 'git config --get gitlive.branch.develop.name',
            4 => 'git rev-parse --git-dir 2> /dev/null',
            5 => 'git config --get gitlive.branch.master.name',
            6 => 'git rev-parse --git-dir 2> /dev/null',
            7 => 'git config --get gitlive.branch.hotfix.prefix.name',
            8 => 'git fetch upstream',
            9 => 'git fetch -p upstream',
            10 => 'git fetch unit_deploy',
            11 => 'git fetch -p unit_deploy',
            12 => 'git remote',
            13 => 'git branch -a --no-color',
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\HotfixDriver
     */
    public function testGetBuildRepository()
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
            ->with('git config --get gitlive.branch.hotfix.prefix.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
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
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturn('');

        /*
                $mock->shouldReceive('exec')
                    ->once()

                    ->with('git symbolic-ref HEAD 2> /dev/null', true, null)
                    ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return 'refs/heads/feature/example_1';
            });
        */
        /*
                $mock->shouldReceive('exec')
                    ->once()

                    ->with('git log --pretty=fuller --name-status --left-right upstream/stage..refs/heads/feature/example_1', true, null)
                    ->andReturnUsing(function(...$val) use (&$spy) {
                $spy[] = $val;
                return 'diff text';
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

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch unit_deploy', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p unit_deploy', false, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git remote', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -a --no-color', true, null)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '  remotes/unit_deploy/unit_release/123456789';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $ReleaseDriver = App::make(HotfixDriver::class);

        $res = $ReleaseDriver->getBuildRepository();

        $this->assertSame('unit_release/123456789', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git rev-parse --git-dir 2> /dev/null',
            1 => 'git config --get gitlive.deploy.remote',
            2 => 'git rev-parse --git-dir 2> /dev/null',
            3 => 'git config --get gitlive.branch.develop.name',
            4 => 'git rev-parse --git-dir 2> /dev/null',
            5 => 'git config --get gitlive.branch.master.name',
            6 => 'git rev-parse --git-dir 2> /dev/null',
            7 => 'git config --get gitlive.branch.hotfix.prefix.name',
            8 => 'git fetch upstream',
            9 => 'git fetch -p upstream',
            10 => 'git fetch unit_deploy',
            11 => 'git fetch -p unit_deploy',
            12 => 'git remote',
            13 => 'git branch -a --no-color',
        ], data_get($spy, '*.0'));
    }
}
