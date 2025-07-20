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

namespace Tests\GitLive\Tester\Helper;

use GitLive\Application\Facade as App;
use GitLive\Helper\Resource;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ResourceTest extends TestCase
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            'is_file' => [
                'file' => 'aa.txt',
                'default' => '',
                'expected' => file_get_contents(RESOURCES_DIR . DIRECTORY_SEPARATOR . 'aa.txt')
            ],
            'is_not_file' => [
                'file' => 'help',
                'default' => 'aaaaaaaaaaaaa',
                'expected' => 'aaaaaaaaaaaaa'
            ],
        ];
    }

    /**
     * @covers \Gitlive\Helper\Resource::get
     * @dataProvider getDataProvider()
     * @param mixed $file
     * @param mixed $default
     * @param mixed $expected
     * @return void
     */
    public function testGet($file, $default, $expected): void
    {
        $resource = App::make(Resource::class);
        $this->assertEquals($resource->get($file, $default), $expected);
    }

    /**
     * @return array
     */
    public function helpDataProvider()
    {
        return [
            'is_file' => [
                'file' => 'config:set',
                'default' => '',
                'expected' => file_get_contents(RESOURCES_DIR . DIRECTORY_SEPARATOR . 'help/lang/en_US/config/set.md')
            ],
            'is_not_file' => [
                'file' => 'help',
                'default' => 'aaaaaaaaaaaaa',
                'expected' => 'aaaaaaaaaaaaa'
            ],
        ];
    }
    /**
     * @covers \Gitlive\Helper\Resource::Help
     * @dataProvider HelpDataProvider
     * @param mixed $signature_name
     * @param mixed $default
     * @param mixed $expected
     * @return void
     */
    public function testHelp($signature_name, $default, $expected)
    {
        $resource = App::make(Resource::class);
        $this->assertEquals($resource->help($signature_name, $default), $expected);
    }
}
