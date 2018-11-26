<?php
namespace Tests\GitLive;

use GitLive\Application\Container;
use PHPUnit\Framework\TestCase as TestCaseBase;

use App;
use GitLive\Driver\ConfigDriver;
use GitLive\GitLive;

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
 */
class TestCase extends TestCaseBase
{
    public function setUp()
    {
        parent::setUp();
        App::make(GitLive::class);
        ConfigDriver::reset();
    }

    public function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }

}
