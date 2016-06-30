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
class Driver_MergeTest extends testCaseBase
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
    public function mergeDevelopTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'merge', 'develop']);

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
            'git merge upstream/develop',
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
    public function mergeMasterTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn([__FILE__, 'merge', 'master']);

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
            'git merge upstream/master',
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
