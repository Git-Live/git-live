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
use GitLive\Driver\LatestVersionDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use JapaneseDate\DateTime;
use Tests\GitLive\Tester\TestCase;

/**
 * Class LatestVersionDriverTest
 *
 * @category   GitCommand
 * @package    Tests\GitLive\Driver
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-16
 * @internal
 * @coversNothing
 */
class LatestVersionDriverTest extends TestCase
{
    /**
     * @covers \GitLive\Driver\LatestVersionDriver
     */
    public function testCkNewVersion()
    {
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @covers \GitLive\Driver\LatestVersionDriver
     */
    public function testGetLatestVersion()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(static function (...$val) use (&$spy) {
                //$spy[] = $val;

                return '.git';
            });

        $mock->shouldReceive('exec')
            ->andReturnUsing(static function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            static function () use ($mock) {
                return $mock;
            }
        );

        $LatestVersionDriver = App::make(LatestVersionDriver::class);

        DateTime::setTestNow(DateTime::factory(1544519632));

        $res = $LatestVersionDriver->getLatestVersion();

        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => 'git config --get gitlive.latestversion.fetchtime',
            1 => 'git config --get gitlive.latestversion.update_ck_span',
            2 => 'git config --local gitlive.latestversion.fetchtime "1544519632"',
            3 => 'git config --local gitlive.latestversion.val "' . $res . '"',
        ], data_get($spy, '*.0'));

        $this->assertSame('2.0.', substr($res, 0, 4));
    }
}
