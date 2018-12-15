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

use Example\BindTestExample;
use Example\BindTestInterface;
use GitLive\Application\Container;
use GitLive\GitLive;
use PHPUnit\Framework\TestCase;
use Tests\GitLive\Tester\InvokeTrait;

/**
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
        $this->assertTrue(true);
    }

    /**
     * @covers \GitLive\Application\Container
     */
    public function testSetWith()
    {
        $this->assertTrue(true);
    }

    /**
     * @covers \GitLive\Application\Container
     */
    public function testBind()
    {
        Container::bind(BindTestInterface::class, BindTestExample::class);

        $this->assertSame([
            'Example\BindTestInterface' => 'Example\BindTestExample'
        ], Container::getContainers());
    }

    /**
     * @covers \GitLive\Application\Container
     */
    public function testBuild()
    {
        $Container = new Container();

        $GitLive = $Container->build(GitLive::class);

        $this->assertInstanceOf(GitLive::class, $GitLive);
    }

    /**
     * @covers \GitLive\Application\Container
     */
    public function testNotInstantiable()
    {
        $this->assertTrue(true);
    }
}
