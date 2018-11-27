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
use GitLive\Driver\LogDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\TestCase;

/**
 * @internal
 * @coversNothing
 */
class LogDriverTest extends TestCase
{
    public function testLogDevelop()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true)
            ->andReturn('stage');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true)
            ->andReturn('refs/heads/feature/example_1');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git log --pretty=fuller --name-status --left-right upstream/stage..refs/heads/feature/example_1', true)
            ->andReturn('diff text');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $logDriver = App::make(LogDriver::class);

        $res = $logDriver->logDevelop();

        $this->assertSame('diff text', $res);
    }

    public function testLogMaster()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', true)
            ->andReturn('.git');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true)
            ->andReturn('refs/heads/feature/example_1');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git log --pretty=fuller --name-status --left-right upstream/master..refs/heads/feature/example_1', true)
            ->andReturn('diff text');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $logDriver = App::make(LogDriver::class);

        $res = $logDriver->logMaster();

        $this->assertSame('diff text', $res);
    }
}
