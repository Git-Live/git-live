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
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->once()
        ->andReturn(array(__FILE__, 'start'));


        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing');


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
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->once()
        ->andReturn(array(__FILE__, 'restart'));

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing');

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
     * +--
     *
     * @access      public
     * @return void
     */
    public function initInitInteractiveTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'init'));

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing');

        $instance->shouldReceive('ncecho')
        ->andReturn(false);
        $instance->shouldReceive('chdir')
        ->andReturn(false);


        $instance->shouldReceive('interactiveShell')
        ->andReturnConsecutive(array('git@github.com:SelfUser/git-live.git', 'git@github.com:Git-Live/git-live.git', 'git@github.com:DeployUser/git-live.git', 'unit_testing_clone_dir'));


        $instance->execute();
        $mock_trace             = EnviMockLight::getMockTraceList();
        $command_list           = array();
        $interactive_shell_list = array();
        $chdir_list             = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
            if ($item['method_name'] === 'interactiveShell') {
                $interactive_shell_list[] = $item['arguments'];
            }
            if ($item['method_name'] === 'chdir') {
                $chdir_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git clone --recursive git@github.com:SelfUser/git-live.git unit_testing_clone_dir',
            'git remote add upstream git@github.com:Git-Live/git-live.git',
            'git remote add deploy git@github.com:DeployUser/git-live.git',
        );
        $this->assertSame($needle_command_list, $command_list);

        $needle_interactive_shell_list = array(
            array(
                'Please enter only your remote-repository.',
                false,
            ),

            array(
                'Please enter common remote-repository.',
                false,
            ),

            array(
                array(
                    'Please enter deploying dedicated remote-repository.',
                    'If you return in the blank, it becomes the default setting.',
                    'default:git@github.com:Git-Live/git-live.git',
                ),
                'git@github.com:Git-Live/git-live.git',
            ),

            array(
                array(
                    'Please enter work directory path.',
                    'If you return in the blank, it becomes the default setting.',
                    'default:git-live',
                ),
                'git-live',
            ),
        );

        $this->assertSame($needle_interactive_shell_list, $interactive_shell_list);

        $this->assertSame('unit_testing_clone_dir', $chdir_list[0]);
    }
    /* ----------------------------------------- */



    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function initInitInteractiveSmartTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'init'));

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing');

        $instance->shouldReceive('ncecho')
        ->andReturn(false);
        $instance->shouldReceive('chdir')
        ->andReturn(false);


        $instance->shouldReceive('interactiveShell')
        ->andReturnConsecutive(array('git@github.com:SelfUser/git-live.git', 'git@github.com:Git-Live/git-live.git', 'git@github.com:Git-Live/git-live.git', ''));


        $instance->execute();
        $mock_trace             = EnviMockLight::getMockTraceList();
        $command_list           = array();
        $interactive_shell_list = array();
        $chdir_list             = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
            if ($item['method_name'] === 'interactiveShell') {
                $interactive_shell_list[] = $item['arguments'];
            }
            if ($item['method_name'] === 'chdir') {
                $chdir_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git clone --recursive git@github.com:SelfUser/git-live.git git-live',
            'git remote add upstream git@github.com:Git-Live/git-live.git',
            'git remote add deploy git@github.com:Git-Live/git-live.git',
        );
        $this->assertSame($needle_command_list, $command_list);

        $needle_interactive_shell_list = array(
            array(
                'Please enter only your remote-repository.',
                false,
            ),

            array(
                'Please enter common remote-repository.',
                false,
            ),

            array(
                array(
                    'Please enter deploying dedicated remote-repository.',
                    'If you return in the blank, it becomes the default setting.',
                    'default:git@github.com:Git-Live/git-live.git',
                ),
                'git@github.com:Git-Live/git-live.git',
            ),

            array(
                array(
                    'Please enter work directory path.',
                    'If you return in the blank, it becomes the default setting.',
                    'default:git-live',
                ),
                'git-live',
            ),
        );

        $this->assertSame($needle_interactive_shell_list, $interactive_shell_list);

        $this->assertSame('git-live', $chdir_list[0]);
    }
    /* ----------------------------------------- */




    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function initInitOneLineTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(
            __FILE__, 'init',
            'git@github.com:SelfUser/git-live.git',
            'git@github.com:Git-Live/git-live.git',
            'git@github.com:DeployUser/git-live.git',
            'unit_testing_clone_dir',
            )
        );

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing');

        $instance->shouldReceive('ncecho')
        ->andReturn(false);
        $instance->shouldReceive('chdir')
        ->andReturn(false);


        $instance->execute();
        $mock_trace             = EnviMockLight::getMockTraceList();
        $command_list           = array();
        $interactive_shell_list = array();
        $chdir_list             = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
            if ($item['method_name'] === 'chdir') {
                $chdir_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git clone --recursive git@github.com:SelfUser/git-live.git unit_testing_clone_dir',
            'git remote add upstream git@github.com:Git-Live/git-live.git',
            'git remote add deploy git@github.com:DeployUser/git-live.git',
        );
        $this->assertSame($needle_command_list, $command_list);

        $this->assertSame('unit_testing_clone_dir', $chdir_list[0]);
    }
    /* ----------------------------------------- */



    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function initInitOneLineSimpleTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(
            __FILE__, 'init',
            'git@github.com:SelfUser/git-live.git',
            'git@github.com:Git-Live/git-live.git',
            )
        );

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing');

        $instance->shouldReceive('ncecho')
        ->andReturn(false);
        $instance->shouldReceive('chdir')
        ->andReturn(false);


        $instance->execute();
        $mock_trace             = EnviMockLight::getMockTraceList();
        $command_list           = array();
        $interactive_shell_list = array();
        $chdir_list             = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
            if ($item['method_name'] === 'chdir') {
                $chdir_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git clone --recursive git@github.com:SelfUser/git-live.git git-live',
            'git remote add upstream git@github.com:Git-Live/git-live.git',
            'git remote add deploy git@github.com:Git-Live/git-live.git',
        );
        $this->assertSame($needle_command_list, $command_list);

        $this->assertSame('git-live', $chdir_list[0]);
    }
    /* ----------------------------------------- */




    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function initInitOneLineSmartTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(
            __FILE__, 'init',
            'git@github.com:SelfUser/git-live.git',
            'git@github.com:Git-Live/git-live.git',
            'git@github.com:DeployUser/git-live.git',
            )
        );

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing');

        $instance->shouldReceive('ncecho')
        ->andReturn(false);
        $instance->shouldReceive('chdir')
        ->andReturn(false);


        $instance->execute();
        $mock_trace             = EnviMockLight::getMockTraceList();
        $command_list           = array();
        $interactive_shell_list = array();
        $chdir_list             = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
            if ($item['method_name'] === 'chdir') {
                $chdir_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git clone --recursive git@github.com:SelfUser/git-live.git git-live',
            'git remote add upstream git@github.com:Git-Live/git-live.git',
            'git remote add deploy git@github.com:DeployUser/git-live.git',
        );
        $this->assertSame($needle_command_list, $command_list);

        $this->assertSame('git-live', $chdir_list[0]);
    }
    /* ----------------------------------------- */




    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function initInitOneLineSmartHttpTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(
            __FILE__, 'init',
            'https://github.com/SelfUser/git-live.git',
            'https://github.com/Git-Live/git-live.git',
            'https://github.com/DeployUser/git-live.git',
            )
        );

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing');

        $instance->shouldReceive('ncecho')
        ->andReturn(false);
        $instance->shouldReceive('chdir')
        ->andReturn(false);


        $instance->execute();
        $mock_trace             = EnviMockLight::getMockTraceList();
        $command_list           = array();
        $interactive_shell_list = array();
        $chdir_list             = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
            if ($item['method_name'] === 'chdir') {
                $chdir_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git clone --recursive https://github.com/SelfUser/git-live.git git-live',
            'git remote add upstream https://github.com/Git-Live/git-live.git',
            'git remote add deploy https://github.com/DeployUser/git-live.git',
        );
        $this->assertSame($needle_command_list, $command_list);

        $this->assertSame('git-live', $chdir_list[0]);
    }
    /* ----------------------------------------- */




    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function initInitErrorTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(
            __FILE__, 'init',
            'https:',
            'https:',
            'https:',
            )
        );

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing');

        $instance->shouldReceive('ncecho')
        ->andReturn(false);
        $instance->shouldReceive('chdir')
        ->andReturn(false);

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            // var_dump($e->getMessage());
        }

        $mock_trace = EnviMockLight::getMockTraceList();

        // var_dump($mock_trace);

        $this->assertInstanceOf('exception', $e);
        $this->assertSame('ローカルディレクトリを自動取得できませんでした。', $e->getMessage());
    }
    /* ----------------------------------------- */



    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function initInitError2Test()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(
            __FILE__, 'init',
            'https:',
            'https:',
            )
        );

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing');

        $instance->shouldReceive('ncecho')
        ->andReturn(false);
        $instance->shouldReceive('chdir')
        ->andReturn(false);

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            // var_dump($e->getMessage());
        }

        $mock_trace = EnviMockLight::getMockTraceList();

        // var_dump($mock_trace);

        $this->assertInstanceOf('exception', $e);
        $this->assertSame('ローカルディレクトリを自動取得できませんでした。', $e->getMessage());
    }
    /* ----------------------------------------- */



    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function initInitOneLineSmartNonDeployTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(
            __FILE__, 'init',
            'git@github.com:SelfUser/git-live.git',
            'git@github.com:Git-Live/git-live.git',
            'unit_testing_clone_dir',
            )
        );

        $instance->shouldReceive('getSelfBranch')
        ->once()
        ->andReturn('feature/unit_testing');

        $instance->shouldReceive('ncecho')
        ->andReturn(false);
        $instance->shouldReceive('chdir')
        ->andReturn(false);


        $instance->execute();
        $mock_trace             = EnviMockLight::getMockTraceList();
        $command_list           = array();
        $interactive_shell_list = array();
        $chdir_list             = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
            if ($item['method_name'] === 'chdir') {
                $chdir_list[] = $item['arguments'][0];
            }
        }
        // var_export($command_list);
        $needle_command_list = array(
            'git clone --recursive git@github.com:SelfUser/git-live.git unit_testing_clone_dir',
            'git remote add upstream git@github.com:Git-Live/git-live.git',
        );
        $this->assertSame($needle_command_list, $command_list);

        $this->assertSame('unit_testing_clone_dir', $chdir_list[0]);
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
