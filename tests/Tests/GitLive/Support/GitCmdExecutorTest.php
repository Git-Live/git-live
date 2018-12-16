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

namespace Tests\GitLive\Support;

use App;
use GitLive\Application\Container;
use GitLive\Mock\SystemCommand;
use GitLive\Support\GitCmdExecutor;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\Tester\TestCase;

/**
 * @internal
 * @coversNothing
 */
class GitCmdExecutorTest extends TestCase
{
    protected $spy;
    protected function setUp()
    {
        parent::setUp();

        $this->spy = [];

        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $this->spy[] = $val;

                return '.git';
            });

        $mock->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $this->spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testDiff()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->diff(['-w', 'upstream/master', 'origin/master']);

        $this->assertEquals([
            'git diff -w upstream/master origin/master'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testChdir()
    {
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testBranch()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->branch(['-a', ]);

        $this->assertEquals([
            'git branch -a'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testClone()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->clone(['--config', 'user.author=Suzunone']);

        $this->assertEquals([
            'git clone --config user.author=Suzunone'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testCheckout()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->checkout('master', ['-b', ]);

        $this->assertEquals([
            'git checkout -b master'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testClean()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->clean([]);

        $this->assertEquals([
            'git clean -df'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testTagPush()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->tagPush('origin');

        $this->assertEquals([
            'git push origin --tags'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testTagPull()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->tagPull('origin');

        $this->assertEquals([
            'git pull origin --tags'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testPush()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->push('origin', 'master');

        $this->assertEquals([
            'git push origin master'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testTopLevelDir()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->topLevelDir();

        $this->assertEquals([
            'git rev-parse --show-toplevel'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testPull()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->pull('origin', 'master');

        $this->assertEquals([
            'git pull origin master'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testTag()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->tag([]);

        $this->assertEquals([
            'git tag'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testStash()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->stash(['-u']);

        $this->assertEquals([
            'git stash -u'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testRemote()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->remote(['-v']);

        $this->assertEquals([
            'git remote -v'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testConfig()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->config([]);

        $this->assertEquals([
            'git config'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testReset()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->reset([]);

        $this->assertEquals([
            'git reset --hard HEAD'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testLog()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->log('develop', 'master');

        $this->assertEquals([
            'git log develop..master'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testStatus()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->status(['--long']);

        $this->assertEquals([
            'git status --long'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testFetch()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->fetch(['-a']);

        $this->assertEquals([
            'git fetch -a'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testMerge()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->merge('develop');

        $this->assertEquals([
            'git merge develop'
        ], data_get($this->spy, '*.0'));
    }

    /**
     * @covers \GitLive\Support\GitCmdExecutor
     */
    public function testFetchPullRequest()
    {
        $obj = App::make(GitCmdExecutor::class);

        $obj->fetchPullRequest();

        $this->assertEquals([
            'git fetch upstream \'+refs/pull/*:refs/remotes/pr/*\''
        ], data_get($this->spy, '*.0'));
    }
}
