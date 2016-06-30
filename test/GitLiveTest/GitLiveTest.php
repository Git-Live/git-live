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
class GitLiveTest extends testCaseBase
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
    public function getSelfBranchTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);
        $e        = null;
        try {
            $instance->shouldReceive('exec')
            ->andReturn('refs/heads/unit_testing/unit_testing');

            $res = $instance->getSelfBranch();

        } catch (exception $e) {
        }
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }

        // var_export($command_list);
        $needle_command_list = array (
            'git symbolic-ref HEAD 2>/dev/null',
        );
        $this->assertSame($needle_command_list, $command_list);

        $this->assertNull($e);
        $this->assertSame($res, 'refs/heads/unit_testing/unit_testing');



        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);
        $e        = null;
        try {
            $instance->shouldReceive('exec')
            ->andReturn('');

            $res = $instance->getSelfBranch();

        } catch (exception $e) {
        }
        $mock_trace = EnviMockLight::getMockTraceList();
        $command_list = [];
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }

        // var_export($command_list);
        $needle_command_list = array (
            'git symbolic-ref HEAD 2>/dev/null',
        );
        $this->assertSame($needle_command_list, $command_list);

        $this->assertInstanceOf('exception', $e);



    }
    /* ----------------------------------------- */



    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function enableReleaseTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);
        $e        = null;
        try {
            $instance->getGitCmdExecuter()->shouldReceive('remote')
            ->andReturn(join("\n", ['deploy', 'origin', 'upstream'])."\n");

            $instance->enableRelease();
        } catch (exception $e) {
        }
        $this->assertNull($e);
    }
    /* ----------------------------------------- */


    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function enableReleaseDisabledTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);
        $e        = null;
        try {
            $instance->getGitCmdExecuter()->shouldReceive('remote')
            ->andReturn('origin');
            $instance->enableRelease();
        } catch (exception $e) {
        }
        $this->assertInstanceOf('exception', $e);
    }
    /* ----------------------------------------- */


    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function getHotfixRepositoryTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);
        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", ['develop', 'master', 'remotes/upstream/hotfix/20160629050505'])."\n");
        $e = null;
        try {
            $res = $instance->getHotfixRepository();
        } catch (exception $e) {
        }
        $this->assertSame('hotfix/20160629050505', $res);
        $this->assertNull($e);



        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);
        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", ['develop', 'master'])."\n");

        $e = null;
        try {
            $res = $instance->getHotfixRepository();
        } catch (exception $e) {
        }


        $this->assertInstanceOf('exception', $e);
    }
    /* ----------------------------------------- */


    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function getReleaseRepositoryTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);
        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", ['develop', 'master', 'remotes/upstream/release/20160629050505'])."\n");
        $e = null;
        try {
            $res = $instance->getReleaseRepository();
        } catch (exception $e) {
        }
        $this->assertSame('release/20160629050505', $res);
        $this->assertNull($e);


        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', [], false);
        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", ['develop', 'master'])."\n");

        $e = null;
        try {
            $res = $instance->getReleaseRepository();
        } catch (exception $e) {
        }


        $this->assertInstanceOf('exception', $e);
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
