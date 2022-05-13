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

use GitLive\Application\Application;
use GitLive\Support\FileSystem;
use Symfony\Component\Console\Output\StreamOutput;
use Tests\GitLive\Tester\TestCase;

/**
 * Class FileSystemTest
 *
 * @category   GitCommand
 * @package    Tests\GitLive\Support
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-23
 * @internal
 * @coversNothing
 */
class FileSystemTest extends TestCase
{
    protected $http_status_test = 'https://httpbin.org/status/';
    protected $http_test = 'https://httpbin.org/get';

    /**
     * @covers \GitLive\Support\FileSystem
     */
    public function testPutContents()
    {
        $path = PROJECT_ROOT_DIR . '/storage/unit_testing/file_put_test';
        @unlink($path);
        $fs = new FileSystem();

        $fs->putContents($path, 'suzunone');

        $this->assertIsReadable($path);

        @unlink($path);
    }

    /**
     * @covers \GitLive\Support\FileSystem
     */
    public function testGetContents()
    {
        $fs = new FileSystem();
        $res = $fs->getContents($this->http_test);

        dump($res);

        $this->assertStringContainsString('"Host": "httpbin.org"', $res);
    }

    /**
     * @covers \GitLive\Support\FileSystem
     */
    public function testOutput()
    {
        $fs = new FileSystem();
        ob_start();
        $fs->output('test', 'message');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertStringContainsString('test : message', $output);
    }
    /**
     * @covers \GitLive\Support\FileSystem
     * @see CommandTester
     * @see Application
     * @see App
     */
    public function testOutputWithOutput()
    {
        $output = new StreamOutput(fopen('php://memory', 'wb', false));
        $output->setDecorated(false);

        $fs = new FileSystem($output);

        $fs->output('test', 'message');

        $fs->output('suzunone');

        rewind($output->getStream());
        $display = stream_get_contents($output->getStream());

        $this->assertStringContainsString('test : message', $display);
    }
    /**
     * @covers \GitLive\Support\FileSystem
     */
    public function testGetContentsWithProgress()
    {
        $output = new StreamOutput(fopen('php://memory', 'wb', false));
        $output->setDecorated(false);

        $fs = new FileSystem($output);

        $res = $fs->getContentsWithProgress($this->http_test);

        $this->assertStringContainsString('"Host": "httpbin.org"', $res);
    }

    /**
     * @covers \GitLive\Support\FileSystem
     */
    public function testGetContentsWithProgress301()
    {
        $output = new StreamOutput(fopen('php://memory', 'wb', false));
        $output->setDecorated(false);

        $fs = new FileSystem($output);

        $res = $fs->getContentsWithProgress($this->http_status_test . '301');

        $this->assertStringContainsString('', $res);
    }

    /**
     * @covers \GitLive\Support\FileSystem
     */
    public function testGetContentsWithProgress404()
    {
        $output = new StreamOutput(fopen('php://memory', 'wb', false));
        $output->setDecorated(false);

        $fs = new FileSystem($output);

        $res = $fs->getContentsWithProgress($this->http_status_test . '404');

        $this->assertNotContains('"Host": "httpbin.org"', $res);

        rewind($output->getStream());
        $display = stream_get_contents($output->getStream());
        $this->assertStringContainsString('404', $display);
    }
}
