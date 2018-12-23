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
use GitLive\Support\Envelopment;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class EnvelopmentTest extends TestCase
{
    /**
     * @covers \GitLive\Support\Envelopment
     */
    public function testPutEnv()
    {
        App::make(Envelopment::class)->putEnv('UNIT_TEST_PUT_KEY', 'suzunone');

        $this->assertEquals('suzunone', getenv('UNIT_TEST_PUT_KEY'));

        App::make(Envelopment::class)->putEnv('UNIT_TEST_PUT_KEY', 'eleven');

        $this->assertEquals('eleven', getenv('UNIT_TEST_PUT_KEY'));
    }

    /**
     * @covers \GitLive\Support\Envelopment
     */
    public function testIsWin()
    {
        $this->assertEquals(DIRECTORY_SEPARATOR !== '/', App::make(Envelopment::class)->isWin());
    }

    /**
     * @covers \GitLive\Support\Envelopment
     * @depends testPutEnv
     */
    public function testGetEnv()
    {
        $this->assertEquals('eleven', App::make(Envelopment::class)->getEnv('UNIT_TEST_PUT_KEY'));
        $this->assertNull(App::make(Envelopment::class)->getEnv('UNIT_TEST_PUT_KEY_2'));
        $this->assertEquals('suzunone.eleven', App::make(Envelopment::class)->getEnv('UNIT_TEST_PUT_KEY_2', 'suzunone.eleven'));
    }

    /**
     * @covers \GitLive\Support\Envelopment
     */
    public function testIsDebug()
    {
        $this->assertTrue(App::make(Envelopment::class)->isDebug());
    }
}
