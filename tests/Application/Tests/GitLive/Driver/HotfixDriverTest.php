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
     * @return array
     */
    public function testIsBuildOpen()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');

        $mock->shouldReceive('exec')
            //->once()
            ->with('git config --get gitlive.branch.hotfix.prefix.name', true, null)
            ->andReturn('release/');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturn('unit_deploy');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturn('stage');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturn('master');

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
                    ->andReturn('refs/heads/feature/example_1');
        */
        /*
                $mock->shouldReceive('exec')
                    ->once()

                    ->with('git log --pretty=fuller --name-status --left-right upstream/stage..refs/heads/feature/example_1', true, null)
                    ->andReturn('diff text');
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
            ->andReturn('unit_deploy');

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
    }

    public function testGetBuildRepository()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.hotfix.prefix.name', true, null)
            ->andReturn('unit_release/');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.deploy.remote', true, null)
            ->andReturn('unit_deploy');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturn('stage');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturn('master');

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
                    ->andReturn('refs/heads/feature/example_1');
        */
        /*
                $mock->shouldReceive('exec')
                    ->once()

                    ->with('git log --pretty=fuller --name-status --left-right upstream/stage..refs/heads/feature/example_1', true, null)
                    ->andReturn('diff text');
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
            ->andReturn('unit_deploy');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -a', 256, 256)
            ->andReturn('remotes/unit_deploy/unit_release/123456789');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $ReleaseDriver = App::make(HotfixDriver::class);

        $res = $ReleaseDriver->getBuildRepository();

        $this->assertSame('unit_release/123456789', $res);
    }
}
