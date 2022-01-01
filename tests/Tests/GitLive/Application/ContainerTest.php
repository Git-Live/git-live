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
use GitLive\Driver\ConfigDriver;
use GitLive\GitLive;
use GitLive\Support\FileSystem;
use GitLive\Support\SystemCommand;
use PHPUnit\Framework\TestCase;
use Tests\GitLive\Tester\InvokeTrait;

/**
 * Class ContainerTest
 *
 * @category   GitCommand
 * @package    Tests\GitLive\Application
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
class ContainerTest extends TestCase
{
    use InvokeTrait;

    /**
     * @covers \GitLive\Application\Container
     */
    public function testBindContext()
    {
        $BindWith = new BindTestWithExample();
        Container::bindContext('$' . 'bindTest', $BindWith);
        Container::bindContext('$' . 'text', 'Suzunone');
        Container::bindContext('$' . 'closure', static function () {
            return 'Eleven';
        });

        $Container = new Container();
        /**
         * @var BindTestContextExample $obj
         */
        $obj = $Container->build(BindTestContextExample::class);
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
     * @covers \GitLive\Application\Container
     */
    public function testBuildClosure()
    {
        $obj = new BindTestExample();

        Container::bind(BindTestInterface::class, static function () use ($obj) {
            return $obj;
        });

        $Container = new Container();
        $this->assertSame($obj, $Container->build(static function () {
            return BindTestInterface::class;
        }));

        $this->assertSame($obj, $Container->build(static function () {
            return BindTestInterface::class;
        }));
        $this->assertSame($obj, $Container->build(static function () {
            return BindTestInterface::class;
        }));
        $this->assertSame($obj, $Container->build(static function () {
            return BindTestInterface::class;
        }));
    }

    /**
     * @covers \GitLive\Application\Container
     */
    public function testSetWith()
    {
        Container::bind(BindTestInterface::class, BindTestExample::class);

        $BindWith = new BindTestWithExample();

        $Container = new Container();
        $Container->setWith(['bindTest' => $BindWith]);
        $obj = $Container->build(BindTestDependExample::class);
        $this->assertInstanceOf(BindTestDependExample::class, $obj);
        $this->assertInstanceOf(BindTestWithExample::class, $obj->bindTest);
    }

    /**
     * @covers \GitLive\Application\Container
     */
    public function testBind()
    {
        Container::bind(BindTestInterface::class, BindTestExample::class);

        $this->assertSame([
            'Example\BindTestInterface' => 'Example\BindTestExample',
        ], Container::getContainers());

        $Container = new Container();
        $this->assertInstanceOf(BindTestExample::class, $Container->build(BindTestInterface::class));
    }

    /**
     * @covers \GitLive\Application\Container
     */
    public function testBindDepend()
    {
        Container::bind(BindTestInterface::class, BindTestExample::class);

        $Container = new Container();
        $obj = $Container->build(BindTestDependExample::class);
        $this->assertInstanceOf(BindTestDependExample::class, $obj);
        $this->assertInstanceOf(BindTestExample::class, $obj->bindTest);
    }

    /**
     * @covers \GitLive\Application\Container
     */
    public function testBindWith()
    {
        Container::bind(BindTestInterface::class, BindTestExample::class);

        $Container = new Container();
        $obj = $Container->build(BindTestDependExample::class);
        $this->assertInstanceOf(BindTestDependExample::class, $obj);
        $this->assertInstanceOf(BindTestExample::class, $obj->bindTest);
    }

    public function buildDataProvider()
    {
        return [
            GitLive::class =>  [GitLive::class],
            FileSystem::class =>  [FileSystem::class],
            ConfigDriver::class =>  [ConfigDriver::class],
            SystemCommand::class => [SystemCommand::class],
        ];
    }

    /**
     * @covers \GitLive\Application\Container
     * @dataProvider buildDataProvider
     * @param mixed $class_name
     */
    public function testBuild($class_name)
    {
        $Container = new Container();

        $obj  = $Container->build($class_name);

        $this->assertInstanceOf($class_name, $obj);
    }

    /**
     * @covers \GitLive\Application\Container
     */
    public function testNotInstantiable()
    {
        $this->assertTrue(true);
    }
}
