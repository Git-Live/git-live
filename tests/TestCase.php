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

namespace Tests\GitLive;

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
 * @internal
 * @coversNothing
 */
class TestCase extends TestCaseBase
{
    protected function setUp()
    {
        parent::setUp();

        App::make(GitLive::class);
        ConfigDriver::reset();
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
