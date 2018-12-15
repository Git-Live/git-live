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

use GitLive\GitLive;
use GitLive\Support\GitCmdExecutor;
use GitLive\Support\SystemCommandInterface;

/**
 * Class HotfixDriver
 *
 * @category   GitCommand
 * @package    GitLive\Driver
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-08
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
     */
    public function __construct($GitLive, GitCmdExecutor $gitCmdExecutor, SystemCommandInterface $command)
    {
        parent::__construct($GitLive, $gitCmdExecutor, $command);

        $this->prefix = $this->Driver(ConfigDriver::class)->hotfixPrefix();
    }

    /**
     * @return bool
     */
    public function isBuildOpen()
    {
        return $this->isHotfixOpen();
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getBuildRepository()
    {
        return $this->getHotfixRepository();
    }
}
