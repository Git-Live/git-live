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
class A_MainTest extends testCaseBase
{
    /**
     * +-- 初期化
     *
     * @access public
     * @return void
     */
    public function initialize()
    {
        $this->free();
    }
    /* ----------------------------------------- */

    public function executeTest()
    {
        include __DIR__.'/../Mock/GitLive/MainFunction.php';

        GitLiveMain()
        ->shouldReceive('ini_get')
        ->andReturn(false);

        GitLiveMain()
        ->shouldReceive('date_default_timezone_get')
        ->andReturn(false);
        GitLiveMain()
        ->shouldReceive('date_default_timezone_set')
        ->andReturn(false);

        GitLiveMain()
        ->shouldReceive('function_exists')
        ->andReturn(false);

        GitLiveMain()
        ->shouldReceive('textdomain')
        ->andReturn(false);

        GitLiveMain()
        ->shouldReceive('bind_textdomain_codeset')
        ->andReturn(false);

        GitLiveMain()
        ->shouldReceive('setlocale')
        ->andReturn(false);

        ob_start();
        include __DIR__.'/../../src/main.php';
        $contents = ob_get_contents();
        ob_end_clean();
        $mock_trace = EnviMockLight::getMockTraceList();

        $default_mock_trace = array(
            array(
                'class_name'  => '\\GitLive\\Mock\\GitLive\\Main',
                'method_name' => 'ini_get',
                'arguments'   => array(
                    'date.timezone',
                ),
            ),
            array(
                'class_name'  => '\\GitLive\\Mock\\GitLive\\Main',
                'method_name' => 'date_default_timezone_get',
                'arguments'   => array(
                ),
            ),
            array(
                'class_name'  => '\\GitLive\\Mock\\GitLive\\Main',
                'method_name' => 'date_default_timezone_set',
                'arguments'   =>
                array(
                    'Europe/London',
                ),
            ),
            array(
                'class_name'  => '\\GitLive\\Mock\\GitLive\\Main',
                'method_name' => 'function_exists',
                'arguments'   => array(
                    '\\_',
                ),
            ),
        );
        $this->assertSame($default_mock_trace, $mock_trace);
        $this->assertTrue(mb_ereg('git live feature start <feature name>', $contents) == true);
    }

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
