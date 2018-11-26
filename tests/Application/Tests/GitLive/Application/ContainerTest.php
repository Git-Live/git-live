<?php
/**
 * ContainerTest.php
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      2018/11/23
 */

namespace Tests\GitLive\Application;

use GitLive\Application\Container;
use PHPUnit\Framework\TestCase;
use Example\BindTestInterface;
use Example\BindTestExample;
use Tests\GitLive\InvokeTrait;
use GitLive\GitLive;

class ContainerTest extends TestCase
{
    use InvokeTrait;

    public function testBindContext()
    {
        $this->assertTrue(true);
    }

    public function testSetWith()
    {
        $this->assertTrue(true);

    }

    public function testBind()
    {
        Container::bind(BindTestInterface::class, BindTestExample::class);

        $this->assertEquals([
            'Example\BindTestInterface' => 'Example\BindTestExample'
        ], Container::getContainers());
    }

    public function testBuild()
    {
        $Container = new Container();

        $GitLive = $Container->build(GitLive::class);

        $this->assertInstanceOf(GitLive::class, $GitLive);

    }

    public function testNotInstantiable()
    {
        $this->assertTrue(true);

    }
}
