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
     * +-- デバッグモードオフ
     *
     * @access      public
     * @return void
     */
    public function getFargv1Test()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitBase', array(), false);
        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, '-arst', '-z', '-q', 'neko', '-e=neko', '-h=inu', '--pop:1234', '--open', '1204', '--joinon=0987'));

        $fargv = $instance->getFargv();
        $this->assertSame(array(
              __FILE__                                                   => 0,
              '-a'                                                       => 1,
              '-r'                                                       => 1,
              '-s'                                                       => 1,
              '-t'                                                       => 1,
              '-z'                                                       => 2,
              '-q'                                                       => 3,
              'neko'                                                     => 4,
              '-e'                                                       => 5,
              '-='                                                       => 6,
              '-n'                                                       => 6,
              '-k'                                                       => 5,
              '-o'                                                       => 5,
              '-h'                                                       => 6,
              '-i'                                                       => 6,
              '-u'                                                       => 6,
              '--pop'                                                    => 7,
              '--open'                                                   => 8,
              1204                                                       => 9,
              '--joinon'                                                 => 10,
            ),
            $fargv
        );
    }
    /* ----------------------------------------- */

    /**
     * +-- デバッグモードオフ
     *
     * @access      public
     * @return void
     */
    public function getFargv2Test()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitBase', array(), false);
        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, '-', '--', '-=', '--=', '-:', '--:'));

        $fargv = $instance->getFargv();
        $this->assertSame(array(
              __FILE__ => 0,
              '-'      => 1,
              '--'     => 6,
              '-='     => 3,
              '-:'     => 5,
            ),
            $fargv
        );
    }
    /* ----------------------------------------- */

    /**
     * +-- デバッグモードオフ
     *
     * @access      public
     * @return void
     */
    public function getOptionTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitBase', array(), false);
        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, '-arst', '-z', '-q', 'neko', '-e=pengin', '-h=inu', '--pop:1234', '--open', '1204', '--joinon=0987'));

        $fargv = $instance->getFargv();
        $this->assertSame(array(
              __FILE__   => 0,
              '-a'       => 1,
              '-r'       => 1,
              '-s'       => 1,
              '-t'       => 1,
              '-z'       => 2,
              '-q'       => 3,
              'neko'     => 4,
              '-e'       => 5,
              '-='       => 6,
              '-p'       => 5,
              '-n'       => 6,
              '-g'       => 5,
              '-i'       => 6,
              '-h'       => 6,
              '-u'       => 6,
              '--pop'    => 7,
              '--open'   => 8,
              1204       => 9,
              '--joinon' => 10,
            ),
            $fargv
        );

        $this->assertTrue($instance->isOption('-a'));
        $this->assertTrue($instance->isOption('-r'));
        $this->assertTrue($instance->isOption('-s'));
        $this->assertTrue($instance->isOption('-t'));

        $this->assertSame('neko', $instance->getOption('-q'));
        $this->assertSame('pengin', $instance->getOption('-e'));
        $this->assertSame('inu', $instance->getOption('-h'));
        $this->assertSame('1234', $instance->getOption('--pop'));
        $this->assertSame('1204', $instance->getOption('--open'));
        $this->assertSame('0987', $instance->getOption('--joinon'));
        $this->assertSame(false, $instance->getOption('--gggggg'));
        $this->assertSame(true, $instance->getOption('--ddddd', true));
    }
    /* ----------------------------------------- */

    /**
     * +-- デバッグモードオフ
     *
     * @access      public
     * @return void
     */
    public function getOptionsTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitBase', array(), false);
        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, '-arst', '-z', '-q', 'neko', '-e=pengin', '-h=inu', '--pop:1234', '--open', '1204', '--joinon=0987', '-t', 'aaaa', '-t', 'bbbb'));

        $this->assertSame(array('neko'), $instance->getOptions('-q'));
        $this->assertSame(array('pengin'), $instance->getOptions('-e'));
        $this->assertSame(array('inu'), $instance->getOptions('-h'));
        $this->assertSame(array('1234'), $instance->getOptions('--pop'));
        $this->assertSame(array('1204'), $instance->getOptions('--open'));
        $this->assertSame(array('0987'), $instance->getOptions('--joinon'));
        $this->assertSame(array('aaaa', 'bbbb'), $instance->getOptions('-t'));
        $this->assertSame(array(), $instance->getOptions('--gggggg'));
        $this->assertSame(array(), $instance->getOptions('--ddddd', true));
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
