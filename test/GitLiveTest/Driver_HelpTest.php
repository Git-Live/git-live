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
     * @return void
     */
    public function executeBlankTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->once()
        ->andReturn(array(__FILE__));

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
     * @return void
     */
    public function executeReleaseBlankTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release'));

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
     * @return void
     */
    public function executeReleaseUndefinedOptionTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'undefined_option'));

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
     * @return void
     */
    public function executePullRequestBlankTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'pr'));

        ob_start();
        $instance->execute();
        $contents = ob_get_contents();
        ob_end_clean();
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertSame('getArgv', $mock_trace[0]['method_name']);
        $this->assertTrue(mb_ereg('git live feature start <feature name>', $contents) == true);

        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'pr', 'track'));

        ob_start();
        $instance->execute();
        $contents = ob_get_contents();
        ob_end_clean();
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertSame('getArgv', $mock_trace[0]['method_name']);
        $this->assertTrue(mb_ereg('git live feature start <feature name>', $contents) == true);

        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'pr', 'merge'));

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
     * @return void
     */
    public function executePullRequestUndefinedOptionTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'pr', 'undefined_option'));

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
     * @return void
     */
    public function executeLogBlankTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'log'));

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
     * @return void
     */
    public function executeLogUndefinedOptionTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'log', 'undefined_option'));

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
     * @return void
     */
    public function executeMergeBlankTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'merge'));

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
     * @return void
     */
    public function executeMergeUndefinedOptionTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'merge', 'undefined_option'));

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
     * @return void
     */
    public function executeFeatureBlankTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'feature'));

        ob_start();
        $instance->execute();
        $contents = ob_get_contents();
        ob_end_clean();
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertSame('getArgv', $mock_trace[0]['method_name']);
        $this->assertTrue(mb_ereg('git live feature start <feature name>', $contents) == true);

        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'feature', 'start'));

        ob_start();
        $instance->execute();
        $contents = ob_get_contents();
        ob_end_clean();
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertSame('getArgv', $mock_trace[0]['method_name']);
        $this->assertTrue(mb_ereg('git live feature start <feature name>', $contents) == true);

        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'feature', 'track'));

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
     * @return void
     */
    public function executeFeatureUndefinedOptionTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'feature', 'undefined_option'));

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
     * @return void
     */
    public function executeHotfixBlankTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix'));

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
     * @return void
     */
    public function executeHotfixUndefinedOptionTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'undefined_option'));

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
     * @return void
     * @depends    executeBlankTest
     */
    public function executeHelpTest($instance)
    {
        EnviMockLight::free($instance);
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);
        $instance->shouldReceive('getArgv')
        ->once()
        ->andReturn(array(__FILE__, 'help'));

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
     * @return void
     * @depends    executeBlankTest
     */
    public function executeVersionTest($instance)
    {
        EnviMockLight::free($instance);
        $instance->shouldReceive('getArgv')
        ->once()
        ->andReturn(array(__FILE__, '-v'));

        ob_start();
        $instance->execute();
        $contents = ob_get_contents();
        ob_end_clean();

        $mock_trace = EnviMockLight::getMockTraceList();
        $this->assertSame('getArgv', $mock_trace[0]['method_name']);
        $this->assertTrue(mb_ereg('Git Live version', $contents) == true);

        EnviMockLight::free($instance);
        $instance->shouldReceive('getArgv')
        ->once()
        ->andReturn(array(__FILE__, '--version'));

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
