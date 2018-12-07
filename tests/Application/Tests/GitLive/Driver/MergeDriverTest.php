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
use GitLive\Driver\MergeDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\TestCase;

/**
 * @internal
 * @coversNothing
 */
class MergeDriverTest extends TestCase
{
    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\MergeDriver
     */
    public function testStateDevelop()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');
        /*
                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
                    ->andReturn('');

                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
                    ->andReturn('');
        */

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturn('stage');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/stage --stdout', 256, 256)
            ->andReturn('');
        /*
        $mock->shouldReceive('exec')
            ->once()
            ->with('git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/stage --stdout| git apply --check', 256, 256)
            ->andReturn('');
*/
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $MergeDriver = App::make(MergeDriver::class);

        $MergeDriver->stateDevelop();

        $this->assertTrue(true);
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\MergeDriver
     */
    public function testStateMaster()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');
        /*
                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
                    ->andReturn('');

                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
                    ->andReturn('');
        */

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.master.name', true, null)
            ->andReturn('master');
        $mock->shouldReceive('exec')
            ->twice()
            ->with('git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/master --stdout', 256, 256)
            ->andReturn('error: test.file: No such file or directory');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/master --stdout| git apply --check', 256, 256)
            ->andReturn('error: test.file: No such file or directory');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git format-patch `git rev-parse --abbrev-ref HEAD`..upstream/master --stdout| git apply --check', false, 256)
            ->andReturn('error: test.file: No such file or directory');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $MergeDriver = App::make(MergeDriver::class);

        $res = $MergeDriver->stateMaster();

        $this->assertSame('error: test.file: No such file or directory', $res);
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\MergeDriver
     */
    public function testMergeDevelop()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');
        /*
                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
                    ->andReturn('');

                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
                    ->andReturn('');
        */

        $mock->shouldReceive('exec')
            ->once()
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturn('stage');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch --all', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git merge upstream/stage', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $MergeDriver = App::make(MergeDriver::class);

        $MergeDriver->mergeDevelop();

        $this->assertTrue(true);
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\DriverBase
     * @covers \GitLive\Driver\MergeDriver
     */
    public function testMergeMaster()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturn('.git');
        /*
                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.ignore', true, null)
                    ->andReturn('');

                $mock->shouldReceive('exec')
                    ->once()
                    ->with('git config --get gitlive.branch.feature.prefix.name', true, null)
                    ->andReturn('');
        */

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
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch upstream', false, null)
            ->andReturn('');
        $mock->shouldReceive('exec')
            ->once()
            ->with('git fetch -p upstream', false, null)
            ->andReturn('');

        $mock->shouldReceive('exec')
            ->once()
            ->with('git merge upstream/master', false, null)
            ->andReturn('');

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $MergeDriver = App::make(MergeDriver::class);

        $MergeDriver->mergeMaster();

        $this->assertTrue(true);
    }
}
