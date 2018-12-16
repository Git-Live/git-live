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

use App;
use GitLive\Application\Container;
use GitLive\Driver\ConfigDriver;
use GitLive\GitLive;
use PHPUnit\Framework\TestCase as TestCaseBase;

/**
 * Class TestCase
 *
 * @category   GitCommand
 * @package Tests\GitLive
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      2018/11/23
 *
 * @coversNothing
 * @codeCoverageIgnore
 * @mixin InvokeTrait
 * @mixin MakeGitTestRepoTrait
 * @mixin CommandTestTrait
 *
 */
abstract class TestCase extends TestCaseBase
{
    protected $git_live = 'git live';
    protected function setUp()
    {
        parent::setUp();

        $this->git_live = 'php ' . dirname(dirname(__DIR__)) . '/bin/git-live.phar';

        App::make(GitLive::class);
        ConfigDriver::reset();

        $self_reflection = new \ReflectionClass($this);
        $traits = collect($self_reflection->getTraitNames());

        // テスト用Gitリポジトリの使用
        if ($traits->search(MakeGitTestRepoTrait::class) !== false) {
            $this->makeGitTestRepoTraitBoot();
        }

        // コマンドラインテスト用処理(MakeGitTestRepoTraitに依存)
        if ($traits->search(CommandTestTrait::class) !== false) {
            $this->commandTestTraitBoot();
        }
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
