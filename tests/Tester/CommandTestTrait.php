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

                $descriptorspec = [
                    0 => ['pipe', 'r'],
                    1 => ['pipe', 'w'],
                    2 => ['pipe', 'w'],
                ];
                // GIT_CEILING_DIRECTORIES を設定することで、テストリポジトリの外側(プロジェクト本体の .git)に
                // git が遡らないようにする。local_test_repository が存在しない・壊れている場合でも
                // プロジェクトのリポジトリを誤って操作するのを防ぐ。
                $gitCeilingDir = dirname((string)$this->local_test_repository);
                $env = array_merge(getenv() ?: [], ['GIT_CEILING_DIRECTORIES' => $gitCeilingDir]);
                $process = proc_open(['sh', '-c', $val[0]], $descriptorspec, $cmdPipes, $this->local_test_repository, $env);
                $res = '';
                if (is_resource($process)) {
                    fclose($cmdPipes[0]);
                    $res = stream_get_contents($cmdPipes[1]) . stream_get_contents($cmdPipes[2]);
                    fclose($cmdPipes[1]);
                    fclose($cmdPipes[2]);
                    proc_close($process);
                }

                dump($val);
                dump($res);

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
            ->with('git config --get gitlive.branch.develop.name', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $this->spy[] = $val;

                return 'staging';
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
            ->with('git branch -a --no-color', true, null)
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
