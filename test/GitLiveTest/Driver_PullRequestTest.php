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
class Driver_PullRequestTest extends testCaseBase
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
    public function prPullTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'pr', 'pull'));

        $instance->shouldReceive('getSelfBranchRef')
        ->once()
        ->andReturn('refs/heads/pullreq/10');

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

            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',

            'git fetch upstream \'+refs/pull/*:refs/remotes/pr/*\'',
            'git pull upstream pull/10/head',

        );
        $this->assertSame($needle_command_list, $command_list);

        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'pr', 'pull'));

        $instance->shouldReceive('getSelfBranchRef')
        ->once()
        ->andReturn('refs/heads/master');

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
    public function prMergeTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'pr', 'merge', '111'));

        $instance->shouldReceive('getSelfBranchRef')
        ->once()
        ->andReturn('refs/heads/feature/unit_testing');

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

            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',

            'git fetch upstream \'+refs/pull/*:refs/remotes/pr/*\'',
            'git pull upstream pull/111/head',
        );
        $this->assertSame($needle_command_list, $command_list);

        // トラックされたプルリクエスト用の処理
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'pr', 'merge', '101'));

        $instance->shouldReceive('getSelfBranchRef')
        ->once()
        ->andReturn('refs/heads/pullreq/10');

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

            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',

            'git fetch upstream \'+refs/pull/*:refs/remotes/pr/*\'',
            'git pull upstream pull/101/head',
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
    public function prTrackTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'pr', 'track', '111'));

        $instance->shouldReceive('getSelfBranchRef')
        ->once()
        ->andReturn('refs/heads/feature/unit_testing');

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

            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',

            'git fetch upstream \'+refs/pull/*:refs/remotes/pr/*\'',
            'git checkout -b pullreq/111 remotes/pr/111/head',
        );
        $this->assertSame($needle_command_list, $command_list);

        // トラックされたプルリクエスト用の処理
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'pr', 'track', '101'));

        $instance->shouldReceive('getSelfBranchRef')
        ->once()
        ->andReturn('refs/heads/pullreq/10');

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

            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',

            'git fetch upstream \'+refs/pull/*:refs/remotes/pr/*\'',
            'git checkout -b pullreq/101 remotes/pr/101/head',
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
