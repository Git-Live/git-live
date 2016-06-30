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
class Driver_FeatureTest extends testCaseBase
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
    public function featureStartTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'feature', 'start', 'unit_testing']);

        $instance->execute();
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch --all',
            'git checkout upstream/develop',
            'git checkout -b feature/unit_testing',
        );
        $this->assertSame($needle_command_list, $command_list);


        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'feature', 'start', 'feature/unit_testing2']);

        $instance->execute();
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch --all',
            'git checkout upstream/develop',
            'git checkout -b feature/unit_testing2',
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
    public function featurePublishTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'feature', 'publish', 'unit_testing']);

        $instance->execute();
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array (
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch --all',
            'git push upstream feature/unit_testing',
        );
        $this->assertSame($needle_command_list, $command_list);



        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'feature', 'publish']);

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing2');

        $instance->execute();
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array (
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch --all',
            'git push upstream feature/unit_testing2',
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
    public function featurePushTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'feature', 'push', 'unit_testing']);

        $instance->execute();
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array (
            'git fetch upstream',
            'git fetch -p upstream',
            'git push origin feature/unit_testing',
        );
        $this->assertSame($needle_command_list, $command_list);



        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'feature', 'push']);

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing2');

        $instance->execute();
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array (
            'git fetch upstream',
            'git fetch -p upstream',
            'git push origin feature/unit_testing2',
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
    public function featurePullTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'feature', 'pull', 'unit_testing']);

        $instance->execute();
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array (
            'git fetch upstream',
            'git fetch -p upstream',
            'git pull upstream feature/unit_testing',
        );
        $this->assertSame($needle_command_list, $command_list);



        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'feature', 'pull']);

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing2');

        $instance->execute();
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array (
            'git fetch upstream',
            'git fetch -p upstream',
            'git pull upstream feature/unit_testing2',
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
    public function featureTrackTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'feature', 'track', 'unit_testing']);

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('master');

        $instance->execute();
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array (
            'git fetch upstream',
            'git fetch -p upstream',
            'git pull upstream feature/unit_testing',
            'git checkout feature/unit_testing',
        );
        $this->assertSame($needle_command_list, $command_list);



        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'feature', 'track', 'feature/unit_testing2']);

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing2');

        $instance->execute();
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array (
            'git fetch upstream',
            'git fetch -p upstream',
            'git pull upstream feature/unit_testing2',
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
    public function featureCloseTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'feature', 'close', 'unit_testing']);

        $instance->execute();
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        $needle_command_list = array (
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch --all',
            'git push upstream :feature/unit_testing',
            'git push origin :feature/unit_testing',
            'git checkout develop',
            'git branch -D feature/unit_testing',
        );
        $this->assertSame($needle_command_list, $command_list);



        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'feature', 'close']);

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing2');

        $instance->execute();
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        $needle_command_list = array (
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch --all',
            'git push upstream :feature/unit_testing2',
            'git push origin :feature/unit_testing2',
            'git checkout develop',
            'git branch -D feature/unit_testing2',
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
