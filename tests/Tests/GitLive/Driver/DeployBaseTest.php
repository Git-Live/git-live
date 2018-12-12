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
use GitLive\Driver\HotfixDriver;
use GitLive\Driver\ReleaseDriver;
use GitLive\GitCmdExecutor;
use GitLive\GitLive;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\TestCase;

/**
 * @internal
 * @coversNothing
 */
class DeployBaseTest extends TestCase
{
    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testBuildSync()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'v2.0';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isBuildOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $ReleaseDriver->boot();
        $ReleaseDriver->buildSync();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git fetch --all",
            9 => "git fetch -p",
            10 => "git fetch upstream",
            11 => "git fetch -p upstream",
            12 => "git fetch unit_deploy",
            13 => "git fetch -p unit_deploy",
            14 => "git checkout remote/unit_deploy/unit_release/unit_test_release_1234",
            15 => "git checkout -b unit_release/unit_test_release_1234",
            16 => "git pull unit_deploy unit_release/unit_test_release_1234",
            17 => "git pull upstream unit_release/unit_test_release_1234",
            18 => "git push upstream unit_release/unit_test_release_1234",
            19 => "git push unit_deploy unit_release/unit_test_release_1234",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testIsBuildOpen()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'v2.0';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isReleaseOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isReleaseOpen')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $res = $ReleaseDriver->isBuildOpen();

        $this->assertTrue($res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testGetHotfixRepository()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.hotfix.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'hotfix/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'v2.0';
            });

        $systemCommand->shouldReceive('exec')
            ->with('git branch -a', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '  develop
  feature/v.1.0.0
  feature/v1
  feature/v2.0.0
  hotfix/20181202175520-rc3
  hotfix/r20181204221944
  local_only
  master
  v1.0
* v2.0
  v2.0.0
  remotes/unit_deploy/hotfix/unit_test_hotfix_branch_1234
  remotes/deploy/v1.0
  remotes/deploy/v2.0
  remotes/origin/0.X.X_newtest
  remotes/origin/HEAD -> origin/master
  remotes/origin/develop
  remotes/origin/feature/20171204_console
  remotes/origin/feature/20180115
  remotes/origin/feature/20180116
  remotes/origin/feature/v1.x
  remotes/origin/master
  remotes/origin/mod_test
  remotes/origin/origin_only
  remotes/upstream/0.X.X_newtest
  remotes/upstream/HEAD -> origin/master
  remotes/upstream/develop
  remotes/upstream/feature/20171204_console
  remotes/upstream/feature/20180115
  remotes/upstream/feature/20180116
  remotes/upstream/feature/v1.x
  remotes/upstream/master
  remotes/upstream/mod_testupstream
  remotes/upstream/upstream_only
  remotes/upstream/upstream_only
  remotes/upstream/hotfix/unit_test_hotfix_branch_1234
  remotes/deploy/hotfix/unit_test_hotfix_branch_1234
  ';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            HotfixDriver::class . '[isHotfixOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );

        $ReleaseDriver->shouldReceive('isHotfixOpen')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        // $ReleaseDriver->register();
        $res = $ReleaseDriver->getHotfixRepository();

        $this->assertSame('hotfix/unit_test_hotfix_branch_1234', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.hotfix.prefix.name",
            8 => "git branch -a",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testIsHotfixOpen()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.hotfix.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'hotfix/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            HotfixDriver::class . '[isBuildOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $res = $ReleaseDriver->isHotfixOpen();

        $this->assertFalse($res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.hotfix.prefix.name",
            8 => "git branch -a",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testBuildTrack()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isBuildOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $ReleaseDriver->buildTrack();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git checkout remote/unit_deploy/unit_release/unit_test_release_1234",
            9 => "git checkout -b unit_release/unit_test_release_1234",
            10 => "git pull upstream unit_release/unit_test_release_1234",
            11 => "git pull unit_deploy unit_release/unit_test_release_1234",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testBuildOpen()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isHotfixOpen,isReleaseOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });
        $ReleaseDriver->shouldReceive('isHotfixOpen')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });
        $ReleaseDriver->shouldReceive('isReleaseOpen')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $res = $ReleaseDriver->buildOpen('20181209031124');

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git branch -a",
            9 => "git checkout upstream/stage",
            10 => "git checkout -b unit_release/20181209031124",
            11 => "git push upstream unit_release/20181209031124",
            12 => "git push unit_deploy unit_release/20181209031124",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testBuildOpenWithReleaseTag()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isHotfixOpen,isReleaseOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });
        $ReleaseDriver->shouldReceive('isHotfixOpen')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });
        $ReleaseDriver->shouldReceive('isReleaseOpen')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $ReleaseDriver->buildOpenWithReleaseTag('unit_test_release_tag', '20181209032006');

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git branch -a",
            9 => "git checkout upstream/stage",
            10 => "git checkout -b unit_release/20181209032006 refs/tags/unit_test_release_tag ",
            11 => "git push upstream unit_release/20181209032006",
            12 => "git push unit_deploy unit_release/20181209032006",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testBuildState()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isBuildOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $res = $ReleaseDriver->buildState();

        $this->assertSame('release is open.', trim($res));

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git log --pretty=fuller --name-status --no-merges unit_deploy/master..unit_release/unit_test_release_1234",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testBuildStateClose()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isBuildOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $res = $ReleaseDriver->buildState();

        $this->assertSame('release is close.', trim($res));

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testEnableRelease()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isBuildOpen,getBuildRepository,isCleanOrFail,isBranchExists]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $ReleaseDriver->enableRelease();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git remote",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     * @expectedException Exception
     */
    public function testEnableReleaseError()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'none';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isBuildOpen,getBuildRepository,isCleanOrFail,isBranchExists]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $ReleaseDriver->enableRelease();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git remote",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testGetBuildRepository()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git branch -a', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '  remotes/unit_deploy/unit_release/123456';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isBuildOpen,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $res = $ReleaseDriver->getBuildRepository();

        $this->assertSame('unit_release/123456', $res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git branch -a",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testGetReleaseRepository()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $systemCommand->shouldReceive('exec')
            ->with('git branch -a', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '  develop
  feature/v.1.0.0
  feature/v1
  feature/v2.0.0
  hotfix/20181202175520-rc3
  hotfix/r20181204221944
  local_only
  master
  v1.0
* v2.0
  v2.0.0
  remotes/unit_deploy/unit_release/unit_test_release_branch_1234
  remotes/deploy/v1.0
  remotes/deploy/v2.0
  remotes/origin/0.X.X_newtest
  remotes/origin/HEAD -> origin/master
  remotes/origin/develop
  remotes/origin/feature/20171204_console
  remotes/origin/feature/20180115
  remotes/origin/feature/20180116
  remotes/origin/feature/v1.x
  remotes/origin/master
  remotes/origin/mod_test
  remotes/origin/origin_only
  remotes/upstream/0.X.X_newtest
  remotes/upstream/HEAD -> origin/master
  remotes/upstream/develop
  remotes/upstream/feature/20171204_console
  remotes/upstream/feature/20180115
  remotes/upstream/feature/20180116
  remotes/upstream/feature/v1.x
  remotes/upstream/master
  remotes/upstream/mod_testupstream
  remotes/upstream/upstream_only
  remotes/upstream/upstream_only
  remotes/upstream/unit_release/unit_test_hotfix_branch_1234
  remotes/deploy/test_test/unit_test_hotfix_branch_1234
  ';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isReleaseOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isReleaseOpen')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $res = $ReleaseDriver->getReleaseRepository();

        $this->assertSame('unit_release/unit_test_release_branch_1234', $res);
        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git branch -a",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testBuildPull()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isBuildOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $res = $ReleaseDriver->buildPull();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git pull upstream unit_release/unit_test_release_1234",
            9 => "git pull unit_deploy unit_release/unit_test_release_1234",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testBuildDestroy()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isBuildOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $res = $ReleaseDriver->buildDestroy();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git push unit_deploy :unit_release/unit_test_release_1234",
            9 => "git push upstream :unit_release/unit_test_release_1234",
            10 => "git branch -d unit_release/unit_test_release_1234",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testIsReleaseOpen()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $systemCommand->shouldReceive('exec')
            ->with('git branch -a', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '  develop
  feature/v.1.0.0
  feature/v1
  feature/v2.0.0
  hotfix/20181202175520-rc3
  hotfix/r20181204221944
  local_only
  master
  v1.0
* v2.0
  v2.0.0
  remotes/unit_deploy/unit_release/unit_test_release_branch_1234
  remotes/deploy/v1.0
  remotes/deploy/v2.0
  remotes/origin/0.X.X_newtest
  remotes/origin/HEAD -> origin/master
  remotes/origin/develop
  remotes/origin/feature/20171204_console
  remotes/origin/feature/20180115
  remotes/origin/feature/20180116
  remotes/origin/feature/v1.x
  remotes/origin/master
  remotes/origin/mod_test
  remotes/origin/origin_only
  remotes/upstream/0.X.X_newtest
  remotes/upstream/HEAD -> origin/master
  remotes/upstream/develop
  remotes/upstream/feature/20171204_console
  remotes/upstream/feature/20180115
  remotes/upstream/feature/20180116
  remotes/upstream/feature/v1.x
  remotes/upstream/master
  remotes/upstream/mod_testupstream
  remotes/upstream/upstream_only
  remotes/upstream/upstream_only
  remotes/upstream/unit_release/unit_test_hotfix_branch_1234
  remotes/deploy/test_test/unit_test_hotfix_branch_1234
  ';
            });
        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isBuildOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $res = $ReleaseDriver->isReleaseOpen();

        $this->assertTrue($res);

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git branch -a",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testBuildPush()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'master';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isBuildOpen,getBuildRepository,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $ReleaseDriver->buildPush();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git checkout unit_release/unit_test_release_1234",
            9 => "git pull upstream unit_release/unit_test_release_1234",
            10 => "git pull unit_deploy unit_release/unit_test_release_1234",
            11 => "git push upstream unit_release/unit_test_release_1234",
        ], data_get($spy, '*.0'));
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testDeployTrack()
    {
        $this->assertFalse(false);
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DeployBase
     * @covers \GitLive\Driver\DriverBase
     */
    public function testBuildClose()
    {
        $GitLive = App::make(GitLive::class);

        $spy = [];
        $systemCommand = \Mockery::mock(SystemCommand::class);
        $systemCommand->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '.git';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.release.prefix.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_release/';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'unit_deploy';
            });

        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'stage';
            });
        $systemCommand->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return 'v2.0';
            });

        $systemCommand->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($systemCommand) {
                return $systemCommand;
            }
        );

        $GitCmdExecutor = App::make(GitCmdExecutor::class);

        $ReleaseDriver = \Mockery::mock(
            ReleaseDriver::class . '[isBuildOpen,getBuildRepository,getSelfBranchRef,isCleanOrFail,isBranchExists,enableRelease]',
            [$GitLive, $GitCmdExecutor, $systemCommand]
        );
        $ReleaseDriver->shouldReceive('isBuildOpen')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('isCleanOrFail')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });
        $ReleaseDriver->shouldReceive('enableRelease')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return true;
            });

        $ReleaseDriver->shouldReceive('isBranchExists')
            ->never()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return false;
            });

        $ReleaseDriver->shouldReceive('getBuildRepository')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'unit_release/unit_test_release_1234';
            });
        $ReleaseDriver->shouldReceive('getSelfBranchRef')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'refs/heads/v2.0';
            });
        $ReleaseDriver->shouldReceive('getSelfBranchRef')
            ->once()
            ->andReturnUsing(function (...$val) use (&$spy) {
                return 'refs/heads/stage';
            });

        /**
         * @var ReleaseDriver $ReleaseDriver
         */
        $ReleaseDriver->buildClose();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git rev-parse --git-dir 2> /dev/null",
            1 => "git config --get gitlive.deploy.remote",
            2 => "git rev-parse --git-dir 2> /dev/null",
            3 => "git config --get gitlive.branch.develop.name",
            4 => "git rev-parse --git-dir 2> /dev/null",
            5 => "git config --get gitlive.branch.master.name",
            6 => "git rev-parse --git-dir 2> /dev/null",
            7 => "git config --get gitlive.branch.release.prefix.name",
            8 => "git checkout unit_deploy/v2.0",
            9 => "git branch -D v2.0",
            10 => "git checkout -b v2.0",
            11 => "git format-patch `git rev-parse --abbrev-ref HEAD`..deploy/unit_release/unit_test_release_1234 --stdout",
            12 => "git merge deploy/unit_release/unit_test_release_1234",
            13 => "git diff unit_deploy/unit_release/unit_test_release_1234 v2.0",
            14 => "git push upstream v2.0",
            15 => "git push unit_deploy v2.0",
            16 => "git checkout upstream/stage",
            17 => "git branch -D stage",
            18 => "git checkout -b stage",
            19 => "git merge unit_deploy/unit_release/unit_test_release_1234",
            20 => "git diff unit_deploy/unit_release/unit_test_release_1234 stage",
            21 => "git push upstream stage",
            22 => "git push unit_deploy :unit_release/unit_test_release_1234",
            23 => "git push upstream :unit_release/unit_test_release_1234",
            24 => "git branch -d unit_release/unit_test_release_1234",
            25 => "git fetch upstream",
            26 => "git checkout upstream/v2.0",
            27 => "git tag runit_test_release_1234",
            28 => "git push upstream --tags",
            29 => "git checkout stage",
        ], data_get($spy, '*.0'));
    }
}
