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
class Driver_PushPullTest extends testCaseBase
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
    public function pushTest()
    {
        // feature
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'push'));


        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('refs/heads/feature/unit_testing');


        $instance->shouldReceive('ncecho')
        ->andReturn(false);

        $instance->execute();
        $mock_trace   = EnviMockLight::getMockTraceList();
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git push origin refs/heads/feature/unit_testing',
        );
        $this->assertSame($needle_command_list, $command_list);


        // develop
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'push'));


        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('refs/heads/develop');


        $instance->shouldReceive('ncecho')
        ->andReturn(false);

        $instance->execute();
        $mock_trace   = EnviMockLight::getMockTraceList();
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git push origin refs/heads/develop',
        );
        $this->assertSame($needle_command_list, $command_list);



        // master
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'push'));


        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('refs/heads/master');


        $instance->shouldReceive('ncecho')
        ->andReturn(false);

        $instance->execute();
        $mock_trace   = EnviMockLight::getMockTraceList();
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git push origin refs/heads/master',
        );
        $this->assertSame($needle_command_list, $command_list);



        // release
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'push'));


        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('refs/heads/release/20160629050505');


        $instance->shouldReceive('ncecho')
        ->andReturn(false);

        $instance->execute();
        $mock_trace   = EnviMockLight::getMockTraceList();
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git push upstream refs/heads/release/20160629050505',
        );
        $this->assertSame($needle_command_list, $command_list);



        // hotfix
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'push'));


        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('refs/heads/hotfix/20160629050505');


        $instance->shouldReceive('ncecho')
        ->andReturn(false);

        $instance->execute();
        $mock_trace   = EnviMockLight::getMockTraceList();
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git push upstream refs/heads/hotfix/20160629050505',
        );
        $this->assertSame($needle_command_list, $command_list);
    }
    /* ----------------------------------------- */



    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function pullTest()
    {

        // feature
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'pull'));


        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('refs/heads/feature/unit_testing');


        $instance->shouldReceive('ncecho')
        ->andReturn(false);

        $instance->execute();
        $mock_trace   = EnviMockLight::getMockTraceList();
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git pull origin refs/heads/feature/unit_testing',
        );
        $this->assertSame($needle_command_list, $command_list);


        // develop
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'pull'));


        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('refs/heads/develop');


        $instance->shouldReceive('ncecho')
        ->andReturn(false);

        $instance->execute();
        $mock_trace   = EnviMockLight::getMockTraceList();
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git pull upstream refs/heads/develop',
        );
        $this->assertSame($needle_command_list, $command_list);



        // master
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'pull'));


        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('refs/heads/master');


        $instance->shouldReceive('ncecho')
        ->andReturn(false);

        $instance->execute();
        $mock_trace   = EnviMockLight::getMockTraceList();
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git pull upstream refs/heads/master',
        );
        $this->assertSame($needle_command_list, $command_list);



        // release
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'pull'));


        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('refs/heads/release/20160629050505');


        $instance->shouldReceive('ncecho')
        ->andReturn(false);

        $instance->execute();
        $mock_trace   = EnviMockLight::getMockTraceList();
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git pull upstream refs/heads/release/20160629050505',
        );
        $this->assertSame($needle_command_list, $command_list);



        // hotfix
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'pull'));


        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('refs/heads/hotfix/20160629050505');


        $instance->shouldReceive('ncecho')
        ->andReturn(false);

        $instance->execute();
        $mock_trace   = EnviMockLight::getMockTraceList();
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git pull upstream refs/heads/hotfix/20160629050505',
        );
        $this->assertSame($needle_command_list, $command_list);
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
