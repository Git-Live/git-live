<?php
/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */

namespace GitLive\Driver;

use App;
use GitLive\GitCmdExecuter;
use GitLive\GitLive;
use GitLive\Support\SystemCommandInterface;

/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
class HotfixDriver extends DeployBase
{

    const MODE = 'hotfix';
    public $prefix;
    public $master_branch;
    public $develop_branch;
    public $deploy_repository_name;

    /**
     * HotfixDriver constructor.
     * @param GitLive                $GitLive
     * @param GitCmdExecuter         $gitCmdExecuter
     * @param SystemCommandInterface $command
     * @throws Exception
     * @throws \ReflectionException
     */
    public function __construct($GitLive, GitCmdExecuter $gitCmdExecuter, SystemCommandInterface $command)
    {

        parent::__construct($GitLive, $gitCmdExecuter, $command);


        $this->prefix = $this->Driver(ConfigDriver::class)->hotfixPrefix();

        $this->deploy_repository_name = App::make(ConfigDriver::class)->deployRemote();
        $this->develop_branch = App::make(ConfigDriver::class)->develop();
        $this->master_branch = App::make(ConfigDriver::class)->master();

        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();
        $this->Driver(FetchDriver::class)->deploy($this->deploy_repository_name);

        $this->enableRelease();
    }


    /**
     * @return bool
     * @throws \ReflectionException
     */
    public function isBuildOpen()
    {

        return $this->isHotfixOpen();

    }

    /**
     * @return string
     * @throws Exception
     * @throws \ReflectionException
     */
    public function getBuildRepository()
    {

        return $this->getHotfixRepository();

    }


}
