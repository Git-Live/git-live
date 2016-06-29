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
class Driver_UpdateTest extends testCaseBase
{
    protected $instance;

    public function initialize()
    {
        $this->instance = new \GitLive\Mock\GitLive;
        $this->free();
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return      void
     */
    public function updateTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);
        $instance->shouldReceive('file_get_contents')
        ->with('https://raw.githubusercontent.com/Git-Live/git-live/master/bin/git-live.phar')
        ->once()
        ->andReturnAugment();

        $instance->shouldReceive('file_put_contents')
        ->with(GIT_LIVE_INSTALL_DIR, 'https://raw.githubusercontent.com/Git-Live/git-live/master/bin/git-live.phar')
        ->once()
        ->andReturnAugment();

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'update']);

        $instance->execute();
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertSame('getArgv', $mock_trace[0]['method_name']);
        $this->assertSame('file_get_contents', $mock_trace[1]['method_name']);
        $this->assertSame('file_put_contents', $mock_trace[2]['method_name']);
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
