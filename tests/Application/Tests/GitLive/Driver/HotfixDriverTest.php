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
use GitLive\Driver\HotfixDriver;
use GitLive\Driver\ReleaseDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\TestCase;

/**
 * @internal
 * @coversNothing
 */
class HptfixDriverTest extends TestCase
{
    /**
     * @throws \ReflectionException
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
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });

        $mock->shouldReceive('exec')
            //->once()
            ->with('git config --get gitlive.branch.hotfix.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'release/';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
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

                    ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
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
            ->andReturn();
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn();

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch unit_deploy', false, null)
            ->andReturn();
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p unit_deploy', false, null)
            ->andReturn();
        $mock->shouldReceive('exec')
            ->once()
            ->with('git remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -a', 256, 256)
            ->andReturn();

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $ReleaseDriver = App::make(HotfixDriver::class);

        $res = $ReleaseDriver->isBuildOpen();

        $this->assertSame(false, $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.deploy.remote",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.develop.name",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.master.name",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.hotfix.prefix.name",
            "git remote",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
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
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.hotfix.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
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

                    ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
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
            ->andReturn();
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn();

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch unit_deploy', false, null)
            ->andReturn();
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p unit_deploy', false, null)
            ->andReturn();
        $mock->shouldReceive('exec')
            ->once()
            ->with('git remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -a', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'remotes/unit_deploy/unit_release/123456789';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $ReleaseDriver = App::make(HotfixDriver::class);

        $res = $ReleaseDriver->getBuildRepository();

        $this->assertSame('unit_release/123456789', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.deploy.remote",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.develop.name",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.master.name",
            "git rev-parse --git-dir 2> /dev/null",
            "git config --get gitlive.branch.hotfix.prefix.name",
            "git remote",
            "git branch -a",
        ], data_get($spy, '*.0'));
    }
}
