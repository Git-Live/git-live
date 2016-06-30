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
class GitBaseTest extends testCaseBase
{
    protected $instance;

    public function initialize()
    {
        $this->instance = new \GitLive\Mock\GitBase;
        $this->free();
    }
    /* ----------------------------------------- */

    /**
     * +-- 色を指定しないdebugメッセージ
     *
     * @access      public
     * @return void
     */
    public function debug1Test()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitBase', array(), false);
        $instance->shouldReceive('ncecho')
        ->with('hogehoge')
        ->once()
        ->andReturnAugment();
        $instance->shouldReceive('cecho')
        ->with('hogehoge')
        ->never()
        ->andReturnAugment();

        $res        = $instance->debug('hogehoge');
        $mock_trace = EnviMockLight::getMockTraceList();
        $this->assertSame('ncecho', $mock_trace[0]['method_name']);
    }
    /* ----------------------------------------- */


    /**
     * +-- 色を指定したdebugメッセージ
     *
     * @access      public
     * @return void
     */
    public function debug2Test()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitBase', array(), false);
        $instance->shouldReceive('ncecho')
        ->with('hogehoge')
        ->never()
        ->andReturnAugment();
        $instance->shouldReceive('cecho')
        ->with('hogehoge', 12)
        ->once()
        ->andReturnAugment();

        $res        = $instance->debug('hogehoge', 12);
        $mock_trace = EnviMockLight::getMockTraceList();
        $this->assertSame('cecho', $mock_trace[0]['method_name']);
    }
    /* ----------------------------------------- */


    /**
     * +-- デバッグモードオフ
     *
     * @access      public
     * @return void
     */
    public function debug3Test()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitBase', array(), false);
        $instance->shouldReceive('isDebug')
        ->andReturn(false);

        $instance->shouldReceive('ncecho')
        ->with('hogehoge')
        ->never()
        ->andReturnAugment();
        $instance->shouldReceive('cecho')
        ->with('hogehoge', 12)
        ->never()
        ->andReturnAugment();

        $res        = $instance->debug('hogehoge', 12);
        $res        = $instance->debug('hogehoge');
        $mock_trace = EnviMockLight::getMockTraceList();
        $this->assertCount(2, $mock_trace);

        $this->assertSame('isDebug', $mock_trace[0]['method_name']);
        $this->assertSame('isDebug', $mock_trace[1]['method_name']);
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
