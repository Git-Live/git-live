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
class Driver_InitTest extends testCaseBase
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
    public function initStartTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->once()
        ->andReturn([__FILE__, 'start']);


        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing');


        $instance->shouldReceive('ncecho')
        ->andReturn(false);

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
            'git fetch --all',
            'git fetch -p',
            'git pull upstream develop',
            'git push origin develop',
            'git pull upstream master',
            'git push origin master',
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
    public function initRestartTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->once()
        ->andReturn([__FILE__, 'restart']);

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing');

        $instance->shouldReceive('ncecho')
        ->andReturn(false);

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
            'git fetch --all',
            'git fetch -p',
            'git checkout -b temp',
            'git branch -d develop',
            'git branch -d master',
            'git push origin :develop',
            'git push origin :master',
            'git checkout upstream/develop',
            'git checkout -b develop',
            'git push origin develop',
            'git checkout upstream/master',
            'git checkout -b master',
            'git push origin master',
            'git fetch --all',
            'git fetch -p',
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
