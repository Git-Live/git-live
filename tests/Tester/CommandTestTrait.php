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

namespace Tests\GitLive\Tester;

use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;

/**
 * Class CommandTestTrait
 *
 * @category   GitCommand
 * @package    Tests\GitLive\Tester
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-16
 * @mixin TestCase
 * @mixin MakeGitTestRepoTrait
 * @codeCoverageIgnore
 */
trait CommandTestTrait
{
    protected $spy;

    protected function commandTestTraitBoot()
    {
        $this->spy = [];

        $self_reflection = new \ReflectionClass($this);
        $traits = collect($self_reflection->getTraitNames());

        // テスト用Gitリポジトリの使用
        if ($traits->search(MakeGitTestRepoTrait::class) === false) {
            $this->commandTestTraitBootDummy();

            return;
        }

        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $this->spy[] = $val;
                $current_work_dir = getcwd();

                chdir($this->local_test_repository);

                $execute_cmd = $val[0] . ' 2>&1';
                $res = `$execute_cmd`;

                dump($val);
                dump($res);
                chdir($current_work_dir);

                return $res;
            });
        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );
    }

    protected function commandTestTraitBootDummy()
    {
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $this->spy[] = $val;

                return '.git';
            });

        $mock->shouldReceive('exec')
            //->once()
            ->with('git symbolic-ref HEAD 2>/dev/null', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $this->spy[] = $val;

                return 'feature/suzunone_branch';
            });

        $mock->shouldReceive('exec')
            //->once()
            ->with('git branch -a', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $this->spy[] = $val;

                return '  remotes/deploy/develop
  remotes/deploy/master
  remotes/deploy/feature/suzunone_branch
  remotes/origin/develop
  remotes/origin/master
  remotes/origin/feature/suzunone_branch
  remotes/upstream/develop
  remotes/upstream/master
  remotes/upstream/feature/suzunone_branch';
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
}
