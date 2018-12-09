<?php
/**
 * BranchDriverTest.php
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-09
 */

namespace Tests\GitLive\Driver;

use App;
use GitLive\Application\Container;
use GitLive\Driver\BranchDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\Collection;
use GitLive\Support\SystemCommandInterface;
use Tests\GitLive\TestCase;

class BranchDriverTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\BranchDriver
     */
    public function testBranchList()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '  develop
  feature/v.1.0.0
  feature/v1
  feature/v2.0.0
  hotfix/20181202175520-rc3
  hotfix/r20181204221944
  master
  v1.0
* v2.0
  v2.0.0
  ';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $BranchDriver = App::make(BranchDriver::class);

        $Collection = $BranchDriver->branchList();

        $this->assertInstanceOf(Collection::class, $Collection);

        $Collection->dump();


        $this->assertEquals(
            [
                "develop",
                "feature/v.1.0.0",
                "feature/v1",
                "feature/v2.0.0",
                "hotfix/20181202175520-rc3",
                "hotfix/r20181204221944",
                "master",
                "v1.0",
                "v2.0",
                "v2.0.0",
            ],
            $Collection->toArray()

        );

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            "git branch",
        ], data_get($spy, '*.0'));

    }

    /**
     * @throws \ReflectionException
     * @covers \GitLive\Driver\BranchDriver
     */
    public function testBranchListAll()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            ->once()
            ->with('git branch -a', true, null)
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '  develop
  feature/v.1.0.0
  feature/v1
  feature/v2.0.0
  hotfix/20181202175520-rc3
  hotfix/r20181204221944
  master
  v1.0
* v2.0
  v2.0.0
  remotes/deploy/0.X.X_newtest
  remotes/deploy/develop
  remotes/deploy/feature/20171204_console
  remotes/deploy/feature/20180115
  remotes/deploy/feature/20180116
  remotes/deploy/feature/v1.x
  remotes/deploy/master
  remotes/deploy/mod_test
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
  ';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $BranchDriver = App::make(BranchDriver::class);

        $Collection = $BranchDriver->branchListAll();

        $this->assertInstanceOf(Collection::class, $Collection);

        $Collection->dump();


        $this->assertEquals(
            [
                0 => "develop",
    1 => "feature/v.1.0.0",
    2 => "feature/v1",
    3 => "feature/v2.0.0",
    4 => "hotfix/20181202175520-rc3",
    5 => "hotfix/r20181204221944",
    6 => "master",
    7 => "v1.0",
    8 => "v2.0",
    9 => "v2.0.0",
    10 => "remotes/deploy/0.X.X_newtest",
    11 => "remotes/deploy/develop",
    12 => "remotes/deploy/feature/20171204_console",
    13 => "remotes/deploy/feature/20180115",
    14 => "remotes/deploy/feature/20180116",
    15 => "remotes/deploy/feature/v1.x",
    16 => "remotes/deploy/master",
    17 => "remotes/deploy/mod_test",
    18 => "remotes/deploy/v1.0",
    19 => "remotes/deploy/v2.0",
    20 => "remotes/origin/0.X.X_newtest",
    21 => "remotes/origin/HEAD",
    22 => "remotes/origin/develop",
    23 => "remotes/origin/feature/20171204_console",
    24 => "remotes/origin/feature/20180115",
    25 => "remotes/origin/feature/20180116",
    26 => "remotes/origin/feature/v1.x",
    27 => "remotes/origin/master",
    28 => "remotes/origin/mod_test",
            ],
            $Collection->toArray()

        );
        dump(data_get($spy, '*.0'));
        $this->assertSame([
            "git branch -a",
        ], data_get($spy, '*.0'));

    }
}
