<?php

/**
 * This file is part of Git-Live
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id\$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 */

namespace GitLive\Driver;

use GitLive\GitCmdExecutor;
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
    /**
     * @var string
     */
    public $prefix;
    /**
     * @var string
     */
    public $master_branch;
    /**
     * @var string
     */
    public $develop_branch;
    /**
     * @var string
     */
    public $deploy_repository_name;

    /**
     * HotfixDriver constructor.
     * @param GitLive                $GitLive
     * @param GitCmdExecutor         $gitCmdExecutor
     * @param SystemCommandInterface $command
     * @throws Exception
     * @throws \ReflectionException
     */
    public function __construct($GitLive, GitCmdExecutor $gitCmdExecutor, SystemCommandInterface $command)
    {
        parent::__construct($GitLive, $gitCmdExecutor, $command);

        $this->prefix = $this->Driver(ConfigDriver::class)->hotfixPrefix();

        $this->Driver(FetchDriver::class)->all();
        $this->Driver(FetchDriver::class)->upstream();
        $this->Driver(FetchDriver::class)->deploy($this->deploy_repository_name);

        $this->enableRelease();
    }

    /**
     * @throws \ReflectionException
     * @return bool
     */
    public function isBuildOpen()
    {
        return $this->isHotfixOpen();
    }

    /**
     * @throws Exception
     * @throws \ReflectionException
     * @return string
     */
    public function getBuildRepository()
    {
        return $this->getHotfixRepository();
    }
}
