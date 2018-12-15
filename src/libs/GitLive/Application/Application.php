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

namespace GitLive\Application;

use App;
use GitLive\GitLive;
use GitLive\Service\CommandLineKernelService;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;

/**
 * Class Application
 *
 * An Application is the container for a collection of commands.
 *
 * It is the main entry point of a Console application.
 *
 * This class is optimized for a standard CLI environment.
 *
 * @category   GitCommand
 * @package    GitLive\Application
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018/11/24
 */
class Application extends ConsoleApplication
{
    /**
     * Application constructor.
     */
    public function __construct()
    {
        $Kernel = App::make(CommandLineKernelService::class);

        $commandLoader = new FactoryCommandLoader(
            $Kernel->app()
        );

        parent::__construct('GIT Live', GitLive::VERSION);
        $this->setCommandLoader($commandLoader);
    }

    /**
     * {@inheritdoc}
     */
    public function getLongVersion()
    {
        $version = file_get_contents(RESOURCES_DIR . DIRECTORY_SEPARATOR . 'aa.txt');
        $version .= parent::getLongVersion();
        if (GitLive::VERSION_CODENAME) {
            $version .= ' - <info>' . GitLive::VERSION_CODENAME . '</info> (@git-version@) ';
        }

        $version .= ' by <comment>Akito</comment> and <comment>Suzunone</comment>';
        $version .= ' - build <info>@release-date@</info>';

        return $version;
    }
}
