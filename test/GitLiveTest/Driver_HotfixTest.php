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
class Driver_HotfixTest extends testCaseBase
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
    public function executeHotfixOpenTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'hotfix', 'open'));

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);
        $instance->shouldReceive('isReleaseOpen')
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
            'git fetch -p deploy',
            'git fetch -p upstream',
            'git branch -a',
            'git checkout upstream/master',
            'git checkout -b hotfix/'.$rep_name,
            'git push upstream hotfix/'.$rep_name,
            'git push deploy hotfix/'.$rep_name,
        );

        $this->assertSame($needle_command_list, $command_list);

        $this->assertTrue(true);

        // isHotfixOpen のエラー処理
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'hotfix', 'open'));
        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);
        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);
        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $this->assertSame('既にhotfix open されています。', $e->getMessage());
        $this->assertInstanceOf('exception', $e);

        // isReleaseOpen のエラー処理
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'hotfix', 'open'));
        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);
        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(true);

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $this->assertSame('既にrelease open されています。', $e->getMessage());
        $this->assertInstanceOf('exception', $e);

        // ローカルのリポジトリ確認
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);
        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/deploy/hotfix/20160629050505'))."\n");

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->twice()
        ->andReturn(array(__FILE__, 'hotfix', 'open'));

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $this->assertSame('既にhotfix open されています。'.'remotes/deploy/hotfix/20160629050505', $e->getMessage());
        $this->assertInstanceOf('exception', $e);
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeHotfixCloseSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'close'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->andReturn('');

        $instance->shouldReceive('getSelfBranch')
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

        $arguments['diff'] = array(array('deploy/hotfix/20160629050505', 'master'), array('deploy/hotfix/20160629050505', 'develop'));

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
            'git fetch -p deploy',
            'git fetch -p upstream',
            'git checkout deploy/master',
            'git checkout -b master',
            'git merge deploy/hotfix/20160629050505',
            'git push upstream master',
            'git push deploy master',
            'git checkout upstream/develop',
            'git checkout -b develop',
            'git merge deploy/hotfix/20160629050505',
            'git push upstream develop',
            'git push deploy :hotfix/20160629050505',
            'git push upstream :hotfix/20160629050505',
            'git fetch upstream',
            'git checkout upstream/master',
            'git tag r20160629050505',
            'git push upstream --tags',
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
    public function executeHotfixCloseSuccessWithTagnameTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'close', 'tag_name'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->andReturn('');

        $instance->shouldReceive('getSelfBranch')
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

        $arguments['diff'] = array(array('deploy/hotfix/20160629050505', 'master'), array('deploy/hotfix/20160629050505', 'develop'));

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
            'git fetch -p deploy',
            'git fetch -p upstream',
            'git checkout deploy/master',
            'git checkout -b master',
            'git merge deploy/hotfix/20160629050505',
            'git push upstream master',
            'git push deploy master',
            'git checkout upstream/develop',
            'git checkout -b develop',
            'git merge deploy/hotfix/20160629050505',
            'git push upstream develop',
            'git push deploy :hotfix/20160629050505',
            'git push upstream :hotfix/20160629050505',
            'git fetch upstream',
            'git checkout upstream/master',
            'git tag tag_name',
            'git push upstream --tags',
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
    public function executeHotfixCloseNotHotfixOpenErrorTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'close'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->andReturn('');

        $instance->shouldReceive('getSelfBranch')
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
        $this->assertSame('hotfix openされていません。', $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeHotfixCloseDiffErrorTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'close'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->once()
        ->andReturnConsecutive(array('差分'));

        $instance->shouldReceive('getSelfBranch')
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
        $this->assertSame("差分\nhotfix closeに失敗しました。", $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeHotfixCloseDiff2SuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'close'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->andReturnConsecutive(array('', '差分'));

        $instance->shouldReceive('getSelfBranch')
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

        $arguments['diff'] = array(array('deploy/hotfix/20160629050505', 'master'), array('deploy/hotfix/20160629050505', 'develop'));

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
            'git fetch -p deploy',
            'git fetch -p upstream',
            'git checkout deploy/master',
            'git checkout -b master',
            'git merge deploy/hotfix/20160629050505',
            'git push upstream master',
            'git push deploy master',
            'git checkout upstream/develop',
            'git checkout -b develop',
            'git merge deploy/hotfix/20160629050505',
            'git push upstream develop',
            'git push deploy :hotfix/20160629050505',
            'git push upstream :hotfix/20160629050505',
            'git fetch upstream',
            'git checkout upstream/master',
            'git tag r20160629050505',
            'git push upstream --tags',
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
    public function executeHotfixCloseGetSelfBranchErrorTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'close'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->andReturn('');

        $instance->shouldReceive('getSelfBranch')
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
        $this->assertSame("hotfix closeに失敗しました。\nmasterがHotfixブランチより進んでいます。", $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeHotfixCloseGetSelfBranchError2Test()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'close'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('diff')
        ->andReturn('');

        $instance->shouldReceive('getSelfBranch')
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
        $this->assertSame("hotfix closeに失敗しました。\ndevelopがHotfixブランチより進んでいます。", $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeHotfixSyncSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'sync'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

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
          'git fetch -p deploy',
          'git fetch -p upstream',
          'git checkout -b hotfix/20160629050505',
          'git pull deploy hotfix/20160629050505',
          'git pull upstream hotfix/20160629050505',
          'git push upstream hotfix/20160629050505',
          'git push deploy hotfix/20160629050505',
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
    public function executeHotfixErrorCannotAutomaticallyMergeTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'sync'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

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
          'git fetch -p deploy',
          'git fetch -p upstream',
          'git checkout -b hotfix/20160629050505',
          'git pull deploy hotfix/20160629050505',
          'git pull upstream hotfix/20160629050505',

        );

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
    public function executeHotfixErrorHotfixNotOpenTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'sync'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

        $instance->getGitCmdExecuter()->shouldReceive('status')
        ->andReturn('ステータスエラー');

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);

        $this->assertSame('hotfix openされていません。', $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeHotfixStatusOpenSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'state'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

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
            'git fetch -p deploy',
            'git fetch -p upstream',
            'git log --pretty=fuller --name-status deploy/master..hotfix/20160629050505',
        );

        $this->assertSame($needle_command_list, $command_list);
        $this->assertSame($mock_trace[count($mock_trace) - 1]['arguments'][0], "hotfix is open.\n");
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeHotfixStatusCloseSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'state'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

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
        $this->assertSame($mock_trace[count($mock_trace) - 1]['arguments'][0], "hotfix is close.\n");
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeHotfixPullSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'pull'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

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
            'git fetch -p deploy',
            'git fetch -p upstream',
            'git pull upstream hotfix/20160629050505',
            'git checkout hotfix/20160629050505',
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
    public function executeHotfixPullErrorHotfixIsNotOpenTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'pull'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);

        $this->assertSame('hotfix openされていません。', $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeHotfixTrackSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'track'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

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
            'git fetch -p deploy',
            'git fetch -p upstream',
            'git pull upstream hotfix/20160629050505',
            'git pull deploy hotfix/20160629050505',
            'git checkout hotfix/20160629050505',
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
    public function executeHotfixTrackErrorHotfixIsNotOpenTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'track'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);

        $this->assertSame('hotfix openされていません。', $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeHotfixPushSuccessTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'push'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

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
            'git fetch -p deploy',
            'git fetch -p upstream',
            'git checkout hotfix/20160629050505',
            'git pull upstream hotfix/20160629050505',
            'git push upstream hotfix/20160629050505',
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
    public function executeHotfixPushErrorHotfixIsNotOpenTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'push'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(false);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

        $e = null;
        try {
            $instance->execute();
        } catch (exception $e) {
        }
        $mock_trace = EnviMockLight::getMockTraceList();

        $this->assertInstanceOf('exception', $e);

        $this->assertSame('hotfix openされていません。', $e->getMessage());
    }
    /* ----------------------------------------- */

    /**
     * +--
     *
     * @access      public
     * @return void
     */
    public function executeHotfixPushErrorStatusErrorTest()
    {
        $instance = EnviMockLight::mock('\GitLive\Mock\GitLive', array(), false);

        $instance->shouldReceive('enableRelease')
        ->andReturn(true);

        $instance->shouldReceive('hotfix')
        ->once()
        ->andNoBypass();

        $instance->shouldReceive('getArgv')
        ->andReturn(array(__FILE__, 'hotfix', 'push'));

        $instance->shouldReceive('isHotfixOpen')
        ->once()
        ->andReturn(true);

        $instance->shouldReceive('isReleaseOpen')
        ->once()
        ->andReturn(false);

        $instance->getGitCmdExecuter()->shouldReceive('branch')
        ->andReturn(join("\n", array('develop', 'master', 'remotes/upstream/hotfix/20160629050505'))."\n");

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
