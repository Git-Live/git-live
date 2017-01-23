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
class Driver_ReleaseTest extends testCaseBase
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
    public function executeReleaseOpenTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'release', 'open'));

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);
        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->execute();

        $mock_trace = EnviMockLight::getMockTraceList();

        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }

        $rep_name            = substr($command_list[count($command_list) - 1], -14);
        $needle_command_list = array(
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch deploy',
            'git fetch -p deploy',
            'git branch -a',
            'git checkout upstream/develop',
            'git checkout -b release/'.$rep_name,
            'git push upstream release/'.$rep_name,
            'git push deploy release/'.$rep_name,
        );

        $this->assertSame($needle_command_list, $command_list);

        $this->assertTrue(true);

        // isReleaseOpen のエラー処理
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'release', 'open'));
        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);
        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);
        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $this->assertSame('既にrelease openされています。', $e->getMessage());
        $this->assertInstanceOf('exception', $e);

        // isHotfixOpen のエラー処理
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'release', 'open'));
        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);
        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $this->assertSame('既にhotfix openされています。', $e->getMessage());
        $this->assertInstanceOf('exception', $e);

        // ローカルのリポジトリ確認
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);
        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/deploy/release/20160629050505'))."\n");

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'release', 'open'));

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $this->assertSame('既にrelease openされています。'."\n".'remotes/deploy/release/20160629050505', $e->getMessage());
        $this->assertInstanceOf('exception', $e);
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleaseCloseSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'close'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $instance->shouldReceive('patchApplyCheck')
        ->twice()
        ->andReturn(true);


        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->andReturn('');

        $instance->shouldReceive('getSelfBranchRef')
        ->twice()
        ->andReturnConsecutive(array('refs/heads/master', 'refs/heads/develop'));

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            var_dump($e->getMessage());
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertNull($e);

        $arguments['diff'] = array(array('deploy/release/20160629050505', 'master'), array('deploy/release/20160629050505', 'develop'));

        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'diff') {
                $this->assertEquals($item['arguments'], array(array_shift($arguments['diff'])));
            }
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }
        $needle_command_list = array(
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch deploy',
            'git fetch -p deploy',
            'git checkout deploy/master',
            'git checkout -b master',
            'git merge deploy/release/20160629050505',
            'git push upstream master',
            'git push deploy master',
            'git checkout upstream/develop',
            'git checkout -b develop',
            'git merge deploy/release/20160629050505',
            'git push upstream develop',
            'git push deploy :release/20160629050505',
            'git push upstream :release/20160629050505',
            'git fetch upstream',
            'git checkout upstream/master',
            'git tag r20160629050505',
            'git push upstream --tags',
            'git checkout develop',
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
    public function executeReleaseCloseForceSuccessWithTagnameTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'close-force', 'tag_name'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $instance->shouldReceive('patchApplyCheck')
        ->twice()
        ->andReturn(true);


        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->andReturn('');

        $instance->shouldReceive('getSelfBranchRef')
        ->twice()
        ->andReturnConsecutive(array('refs/heads/master', 'refs/heads/develop'));

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            var_dump($e->getMessage());
        }
        $mock_trace = EnviMockLight::getMockTraceList();
        $this->assertNull($e);

        $arguments['diff'] = array(array('deploy/release/20160629050505', 'master'), array('deploy/release/20160629050505', 'develop'));

        $arguments['tag_name'] = false;
        $command_list          = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'diff') {
                $this->assertSame($item['arguments'], array(array_shift($arguments['diff'])));
            } elseif ($item['method_name'] === 'exec' && $item['arguments'][0] === 'git tag tag_name') {
                $arguments['tag_name'] = true;
            }
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }

        $this->assertTrue($arguments['tag_name']);

        $needle_command_list = array(
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch deploy',
            'git fetch -p deploy',


            'git checkout deploy/master',
            'git checkout -b master',
            'git merge deploy/release/20160629050505',
            'git push upstream master',
            'git push deploy master',
            'git checkout upstream/develop',
            'git checkout -b develop',
            'git merge deploy/release/20160629050505',
            'git push upstream develop',
            'git push deploy :release/20160629050505',
            'git push upstream :release/20160629050505',
            'git fetch upstream',
            'git checkout upstream/master',
            'git tag tag_name',
            'git push upstream --tags',
            'git checkout develop',
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
    public function executeReleaseCloseNotReleaseOpenErrorTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'close'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");


        $instance->shouldReceive('patchApplyCheck')
        ->twice()
        ->andReturn(true);

        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->andReturn('');

        $instance->shouldReceive('getSelfBranchRef')
        ->twice()
        ->andReturnConsecutive(array('refs/heads/master', 'refs/heads/develop'));

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            // var_dump($e->getMessage());
        }

        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);
        $this->assertSame('release openされていません。', $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleaseCloseDiffErrorTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'close'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $instance->shouldReceive('patchApplyCheck')
        ->once()
        ->andReturn(true);


        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->once()
        ->andReturnConsecutive(array('差分'));

        $instance->shouldReceive('getSelfBranchRef')
        ->once()
        ->andReturnConsecutive(array('refs/heads/master'));

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            // var_dump($e->getMessage());
        }

        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);
        $this->assertSame("差分\nrelease closeに失敗しました。", $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleaseCloseDiff2ErrorTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'close'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $instance->shouldReceive('patchApplyCheck')
        ->twice()
        ->andReturn(true);


        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->andReturnConsecutive(array('', '差分'));

        $instance->shouldReceive('getSelfBranchRef')
        ->twice()
        ->andReturnConsecutive(array('refs/heads/master', 'refs/heads/develop'));

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            // var_dump($e->getMessage());
        }

        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);
        $this->assertSame("release closeに失敗しました。\nDevelopブランチにReleaseより新しいコミットが存在します。", $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleaseClosegetSelfBranchRefErrorTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'close'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $instance->shouldReceive('patchApplyCheck')
        ->twice()
        ->andReturn(true);


        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->andReturn('');

        $instance->shouldReceive('getSelfBranchRef')
        ->once()
        ->andReturnConsecutive(array('', 'refs/heads/develop'));

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            // var_dump($e->getMessage());
        }

        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);
        $this->assertSame("release closeに失敗しました。\nMasterブランチにReleaseより新しいコミットが存在します。", $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleaseClosegetSelfBranchRefError2Test()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'close'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");


        $instance->shouldReceive('patchApplyCheck')
        ->twice()
        ->andReturn(true);

        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->andReturn('');

        $instance->shouldReceive('getSelfBranchRef')
        ->twice()
        ->andReturnConsecutive(array('refs/heads/master', ''));

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            // var_dump($e->getMessage());
        }

        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);
        $this->assertSame("release closeに失敗しました。\nDevelopブランチにReleaseより新しいコミットが存在します。", $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleaseSyncSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'sync'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('status')
        ->andReturn('On branch master
Your branch is ahead of \'origin/master\' by 20 commits.
  (use "git push" to publish your local commits)

nothing to commit, working directory clean');

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            var_dump($e->getMessage());
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertNull($e);
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }

        $needle_command_list = array(
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch deploy',
            'git fetch -p deploy',
            'git checkout remote/deploy/release/20160629050505',
            'git checkout -b release/20160629050505',
            'git pull deploy release/20160629050505',
            'git pull upstream release/20160629050505',
            'git push upstream release/20160629050505',
            'git push deploy release/20160629050505',
        );
        // var_export($command_list);
        $this->assertSame($needle_command_list, $command_list);
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleaseErrorCannotAutomaticallyMergeTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'sync'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('status')
        ->andReturn('ステータスエラー');

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }

        $needle_command_list = array(
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch deploy',
            'git fetch -p deploy',
            'git checkout remote/deploy/release/20160629050505',
            'git checkout -b release/20160629050505',
            'git pull deploy release/20160629050505',
            'git pull upstream release/20160629050505',

        );
        // var_export($command_list);
        $this->assertSame($needle_command_list, $command_list);

        $this->assertSame('ステータスエラー', $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleaseErrorReleaseNotOpenTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'sync'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('status')
        ->andReturn('ステータスエラー');

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);

        $this->assertSame('release openされていません。', $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleaseStatusOpenSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'state'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $instance->shouldReceive('ncecho')
        ->andReturn(false);

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            var_dump($e->getMessage());
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertNull($e);
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }

        $needle_command_list = array(
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch deploy',
            'git fetch -p deploy',

            'git log --pretty=fuller --name-status deploy/master..release/20160629050505',
        );

        $this->assertSame($needle_command_list, $command_list);
        $this->assertSame($mock_trace[count($mock_trace) - 1]['arguments'][0], "release is open.\n");
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleaseStatusCloseSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'state'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $instance->shouldReceive('ncecho')
        ->andReturn(false);

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            var_dump($e->getMessage());
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertNull($e);
        $this->assertSame($mock_trace[count($mock_trace) - 1]['arguments'][0], "release is close.\n");
    }
    /* ----------------------------------------- */

    /**
     * +-- 分岐にないけど、差分があっても無視するパターン
     *
     *
     * @access      public
     * @return void
     */
    public function executeReleaseCloseDiff2SuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'close-force'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->andReturnConsecutive(array('', '差分'));

        $instance->shouldReceive('getSelfBranchRef')
        ->twice()
        ->andReturnConsecutive(array('refs/heads/master', 'refs/heads/develop'));

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            var_dump($e->getMessage());
        }

        $mock_trace = EnviMockLight::getMockTraceList();
        $this->assertNull($e);

        $arguments['diff'] = array(array('deploy/release/20160629050505', 'master'), array('deploy/release/20160629050505', 'develop'));

        $command_list          = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'diff') {
                $this->assertSame($item['arguments'], array(array_shift($arguments['diff'])));
            }
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }

        $needle_command_list = array(
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch deploy',
            'git fetch -p deploy',
            'git checkout deploy/master',
            'git checkout -b master',
            'git merge deploy/release/20160629050505',
            'git push upstream master',
            'git push deploy master',
            'git checkout upstream/develop',
            'git checkout -b develop',
            'git merge deploy/release/20160629050505',
            'git push upstream develop',
            'git push deploy :release/20160629050505',
            'git push upstream :release/20160629050505',
            'git fetch upstream',
            'git checkout upstream/master',
            'git tag r20160629050505',
            'git push upstream --tags',
            'git checkout develop',
        );
        // var_export($command_list);
        $this->assertSame($needle_command_list, $command_list);
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleasePullSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'pull'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            var_dump($e->getMessage());
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertNull($e);
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }

        $needle_command_list = array(
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch deploy',
            'git fetch -p deploy',
            'git pull upstream release/20160629050505',
            'git pull deploy release/20160629050505',
        );

        // var_export($command_list);
        $this->assertSame($needle_command_list, $command_list);
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleasePullErrorReleaseIsNotOpenTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'pull'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);

        $this->assertSame('release openされていません。', $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleaseTrackSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'track'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            var_dump($e->getMessage());
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertNull($e);
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }

        $needle_command_list = array(
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch deploy',
            'git fetch -p deploy',
            'git checkout remote/deploy/release/20160629050505',
            'git checkout -b release/20160629050505',
            'git pull upstream release/20160629050505',
            'git pull deploy release/20160629050505',
        );

        // var_export($command_list);
        $this->assertSame($needle_command_list, $command_list);
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleaseTrackErrorReleaseIsNotOpenTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'track'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);

        $this->assertSame('release openされていません。', $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleasePushSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('isBranchExits')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'push'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('status')
        ->andReturn('On branch master
Your branch is ahead of \'origin/master\' by 20 commits.
  (use "git push" to publish your local commits)

nothing to commit, working directory clean');

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
            var_dump($e->getMessage());
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertNull($e);
        $command_list = array();
        foreach ($mock_trace as $item) {
            if ($item['method_name'] === 'exec') {
                $command_list[] = $item['arguments'][0];
            }
        }

        $needle_command_list = array(
            'git fetch --all',
            'git fetch -p',
            'git fetch upstream',
            'git fetch -p upstream',
            'git fetch deploy',
            'git fetch -p deploy',
            'git checkout release/20160629050505',
            'git pull upstream release/20160629050505',
            'git pull deploy release/20160629050505',
            'git push upstream release/20160629050505',
        );
        // var_export($command_list);

        $this->assertSame($needle_command_list, $command_list);
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleasePushErrorReleaseIsNotOpenTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'push'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);

        $this->assertSame('release openされていません。', $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeReleasePushErrorStatusErrorTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('release')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'release', 'push'));

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->shouldReceive('isBranchExits')
        ->once()
        ->andReturn(true);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/release/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('status')
        ->andReturn('おかしなステータス');

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);

        $this->assertSame('おかしなステータス', $e->getMessage());
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
