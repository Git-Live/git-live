<?php
/**
 * Application.php
 *
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
 * @since      2018/11/24
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
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $Kernel = App::make(CommandLineKernelService::class);


        $commandLoader = new FactoryCommandLoader(
            $Kernel->register()
        );


        parent::__construct('GIT Live', GitLive::VERSION);
        $this->setCommandLoader($commandLoader);
    }

    /**
     * {@inheritdoc}
     */
    public function getLongVersion()
    {
        $version = file_get_contents(RESOURCES_DIR.DIRECTORY_SEPARATOR.'aa.txt');
        $version .= parent::getLongVersion();
        if (GitLive::VERSION_CODENAME) {
            $version .= ' - <info>' . GitLive::VERSION_CODENAME . '</info> (@git-version@) ';
        }

        $version .= ' by <comment>Akito</comment> and <comment>Suzunone</comment>';
        $version .= ' - build <info>@release-date@</info>';

        return $version;
    }
}