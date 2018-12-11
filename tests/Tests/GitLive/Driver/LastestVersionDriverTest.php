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
use GitLive\Driver\FetchDriver;
use GitLive\Driver\LastestVersionDriver;
use GitLive\Mock\SystemCommand;
use GitLive\Support\SystemCommandInterface;
use JapaneseDate\DateTime;
use Tests\GitLive\TestCase;

/**
 * Class LastestVersionDriverTest
 *
 * @package Tests\GitLive\Driver
 * @author      Fumikazu Kitagawa<f.kitagawa@eisys.co.jp>
 * @link        http://eisysgit.dlsite.com/Ci-en/webapp
 * @see         http://eisysgit.dlsite.com/Ci-en/webapp
 *
 * @internal
 * @coversNothing
 */
class LastestVersionDriverTest extends TestCase
{
    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\LastestVersionDriver
     */
    public function testCkNewVersion()
    {
    }

    /**
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @covers \GitLive\Driver\LastestVersionDriver
     */
    public function testGetLatestVersion()
    {
        $spy = [];
        $mock = \Mockery::mock(SystemCommand::class);
        $mock->shouldReceive('exec')
            //->once()
            ->with('git rev-parse --git-dir 2> /dev/null', 256, 256)
            ->andReturnUsing(function (...$val) use (&$spy) {
                //$spy[] = $val;

                return '.git';
            });

        $mock->shouldReceive('exec')
            ->andReturnUsing(function (...$val) use (&$spy) {
                $spy[] = $val;

                return '';
            });

        Container::bind(
            SystemCommandInterface::class,
            function () use ($mock) {
                return $mock;
            }
        );

        $LastestVersionDriver = App::make(LastestVersionDriver::class);

        DateTime::setTestNow(DateTime::factory(1544519632));

        $res = $LastestVersionDriver->getLatestVersion();

        $this->assertSame('1.0.0', $res);
        dump(data_get($spy, '*.0'));
        $this->assertSame([
            0 => "git config --get gitlive.latestversion.fetchtime",
            1 => "git config --get gitlive.latestversion.update_ck_span",
            2 => "git config --local gitlive.latestversion.fetchtime \"1544519632\"",
            3 => "git config --local gitlive.latestversion.val \"1.0.0\"",
        ], data_get($spy, '*.0'));
    }
}
