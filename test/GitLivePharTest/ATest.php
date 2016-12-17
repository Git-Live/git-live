<?php
/**
 * PHP versions 5
 *
 *
 *
 * @category   %%project_category%%
 * @package    %%project_name%%
 * @subpackage %%subpackage_name%%
 * @author     %%your_name%% <%%your_email%%>
 * @copyright  %%your_project%%
 * @license    %%your_license%%
 * @version    GIT: $Id$
 * @link       %%your_link%%
 * @see        http://www.enviphp.net/c/man/v3/core/unittest
 * @since      File available since Release 1.0.0
 * @doc_ignore
 */

/**
 * @category   %%project_category%%
 * @package    %%project_name%%
 * @subpackage %%subpackage_name%%
 * @author     %%your_name%% <%%your_email%%>
 * @copyright  %%your_project%%
 * @license    %%your_license%%
 * @version    GIT: $Id$
 * @link       %%your_link%%
 * @see        http://www.enviphp.net/c/man/v3/core/unittest
 * @since      File available since Release 1.0.0
 * @doc_ignore
 */
class ATest extends testCaseBase
{
    protected $base_dir,$test_dir, $origin_dir, $upstream_dir, $deploy_dir, $local_dir;
    protected $test_bin;

    /**
     * +-- 初期化
     *
     * @access public
     * @return void
     */
    public function initialize()
    {
        $this->free();
    }
    /* ----------------------------------------- */

    public function dataProvider()
    {
        $this->test_dir = dirname(__DIR__).DIRECTORY_SEPARATOR.'data';
        $this->base_dir = dirname(dirname(__DIR__));
        $this->test_bin = $this->base_dir.DIRECTORY_SEPARATOR.'git-live.php';


        chmod($this->test_bin, 0777);


        $this->origin_dir   = $this->test_dir.DIRECTORY_SEPARATOR.'origin.git';
        $this->upstream_dir = $this->test_dir.DIRECTORY_SEPARATOR.'upstream.git';
        $this->deploy_dir   = $this->test_dir.DIRECTORY_SEPARATOR.'deploy.git';
        $this->local_dir    = $this->test_dir.DIRECTORY_SEPARATOR.'local';


        if (is_dir($this->test_dir)) {
            $cmd = "rm -rf {$this->test_dir}";
            `$cmd`;
        }

        mkdir($this->test_dir);
        chdir($this->test_dir);
        mkdir($this->upstream_dir);
        chdir($this->upstream_dir);
        $cmd_std_o =  `git init`;
        file_put_contents($this->upstream_dir.DIRECTORY_SEPARATOR.'test.txt', 'test text data');
        $cmd_std_o =  `git add test.txt`;
        $cmd_std_o =  `git commit -am "init commit"`;
        $cmd_std_o =  `git branch develop`;
        chdir($this->test_dir);

        $cmd       = join(' ', array('git', 'clone', '--recursive', $this->upstream_dir, $this->origin_dir));
        $cmd_std_o = `$cmd 2>&1`;
        $cmd       = join(' ', array('git', 'clone', '--recursive', $this->upstream_dir, $this->deploy_dir));
        $cmd_std_o = `$cmd 2>&1`;
        return array('');
    }

    /**
     * +--
     *
     * @access      public
     * @return void
     * @dataProvider dataProvider
     */
    public function executeInitTest()
    {
        if (!is_dir($this->origin_dir) || !is_dir($this->upstream_dir) || !is_dir($this->deploy_dir) || is_dir($this->local_dir)) {
            return;
        }

        $cmd = join(' ', array($this->test_bin, 'init', $this->origin_dir, $this->upstream_dir, $this->deploy_dir, $this->local_dir));
        $std = `$cmd 2>&1`;

        $this->assertFileExists($this->local_dir.DIRECTORY_SEPARATOR.'test.txt');
        @chdir($this->local_dir);
        $cmd_std_o = `git remote -v`;

        $this->assertTrue(mb_ereg($this->deploy_dir, $cmd_std_o) != false);
        $this->assertRegExp('deploy\\s+'.$this->deploy_dir, $cmd_std_o);
        $this->assertRegExp('origin\\s+'.$this->origin_dir, $cmd_std_o);
        $this->assertRegExp('upstream\\s+'.$this->upstream_dir, $cmd_std_o);
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     * @depend executeInitTest
     */
    public function executeFeatureStartTest()
    {
        if (!is_dir($this->origin_dir) || !is_dir($this->upstream_dir) || !is_dir($this->deploy_dir)) {
            return;
        }
        @chdir($this->local_dir);
        $cmd       = join(' ', array($this->test_bin, 'feature', 'start', 'test_feature'));
        $std       = `$cmd 2>&1`;
        $cmd_std_o = `git branch`;
        $this->assertRegExp('\* feature/test_feature', $cmd_std_o);
    }
    /* ----------------------------------------- */

    /**
     * +-- 終了処理
     *
     * @access public
     * @return void
     */
    public function shutdown()
    {
    }
}
