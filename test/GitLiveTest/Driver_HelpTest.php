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
class Driver_HelpTest extends testCaseBase
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
    public function executeBlankTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->once()
        ->andReturn([__FILE__]);

        ob_start();
        $instance->execute();
        $contents = ob_get_contents();
        ob_end_clean();
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertSame('getArgv', $mock_trace[0]['method_name']);
        $this->assertTrue(mb_ereg('git live feature start <feature name>', $contents) == true);

        return $instance;
    }
    /* ----------------------------------------- */



    /**
     * +--
     *
     * @access      public
     * @return      void
     */
    public function executeReleaseBlankTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->andReturn([__FILE__, 'release']);

        ob_start();
        $instance->execute();
        $contents = ob_get_contents();
        ob_end_clean();
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertSame('getArgv', $mock_trace[0]['method_name']);
        $this->assertTrue(mb_ereg('git live feature start <feature name>', $contents) == true);

        return $instance;
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return      void
     */
    public function executeReleaseUndefinedOptionTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->andReturn([__FILE__, 'release', 'undefined_option']);

        ob_start();
        $instance->execute();
        $contents = ob_get_contents();
        ob_end_clean();
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertSame('getArgv', $mock_trace[0]['method_name']);
        $this->assertTrue(mb_ereg('git live feature start <feature name>', $contents) == true);

        return $instance;
    }
    /* ----------------------------------------- */




    /**
     * +--
     *
     * @access      public
     * @return      void
     * @depends    executeBlankTest
     */
    public function executeHelpTest($instance)
    {
        EnviMockLight::free($instance);
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);
        $instance->shouldReceive('getArgv')
        ->once()
        ->andReturn([__FILE__, 'help']);

        ob_start();
        $instance->execute();
        $contents = ob_get_contents();
        ob_end_clean();

        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertSame('getArgv', $mock_trace[0]['method_name']);
        $this->assertTrue(mb_ereg('git live feature start <feature name>', $contents) == true);
    }
    /* ----------------------------------------- */


    /**
     * +--
     *
     * @access      public
     * @return      void
     * @depends    executeBlankTest
     */
    public function executeVersionTest($instance)
    {
        EnviMockLight::free($instance);
        $instance->shouldReceive('getArgv')
        ->once()
        ->andReturn([__FILE__, '-v']);


        ob_start();
        $instance->execute();
        $contents = ob_get_contents();
        ob_end_clean();

        $mock_trace = EnviMockLight::getMockTraceList();
        $this->assertSame('getArgv', $mock_trace[0]['method_name']);
        $this->assertTrue(mb_ereg('Git Live version', $contents) == true    );


        EnviMockLight::free($instance);
        $instance->shouldReceive('getArgv')
        ->once()
        ->andReturn([__FILE__, '--version']);


        ob_start();
        $instance->execute();
        $contents = ob_get_contents();
        ob_end_clean();

        $mock_trace = EnviMockLight::getMockTraceList();
        $this->assertSame('getArgv', $mock_trace[0]['method_name']);
        $this->assertTrue(mb_ereg('Git Live version', $contents) == true);
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
