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

namespace Tests\GitLive\Application;

use Example\BindTestContextExample;
use Example\BindTestDependExample;
use Example\BindTestExample;
use Example\BindTestInterface;
use Example\BindTestWithExample;
use GitLive\Application\Container;
use GitLive\Application\Facade;
use GitLive\GitLive;
use PHPUnit\Framework\TestCase;
use Tests\GitLive\Tester\InvokeTrait;

/**
 * @internal
 * @coversNothing
 */
class FacadeTest extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();
        Container::reset();
    }

    /**
     * @covers \GitLive\Application\Facade
     */
    public function testMake()
    {
        $BindWith = new BindTestWithExample();
        Container::bindContext('$' . 'bindTest', $BindWith);
        Container::bindContext('$' . 'text', 'Suzunone');
        Container::bindContext('$' . 'closure', function () {
            return 'Eleven';
        });

        /**
         * @var BindTestContextExample $obj
         */
        $obj = Facade::make(BindTestContextExample::class);
        $this->assertInstanceOf(BindTestContextExample::class, $obj);
        $this->assertInstanceOf(BindTestWithExample::class, $obj->bindTest);
        $this->assertEquals('Suzunone', $obj->text);
        $this->assertEquals('Eleven', $obj->closure);
        $this->assertEquals('123456789', $obj->default_value);
        $this->assertNull($obj->nothing);
        $this->assertTrue($obj->is_boot);

        $this->assertEquals(Container::getContextContainers()['$bindTest'], $BindWith);
        Container::reset();

        $this->assertEquals(Container::getContextContainers(), []);
        $this->assertEquals(Container::getContainers(), []);
    }

    /**
     * @covers \GitLive\Application\Facade
     */
    public function testMakeWith()
    {
        Container::bind(BindTestInterface::class, BindTestExample::class);

        $BindWith = new BindTestWithExample();

        $obj = Facade::make(BindTestDependExample::class, ['bindTest' => $BindWith]);
        $this->assertInstanceOf(BindTestDependExample::class, $obj);
        $this->assertInstanceOf(BindTestWithExample::class, $obj->bindTest);
        $this->assertSame($BindWith, $obj->bindTest);
    }

    /**
     * @covers \GitLive\Application\Facade
     */
    public function testMakeError()
    {
        $obj = Facade::make(
            BindTestInterface::class
        );

        $this->assertNull($obj);
    }
}
