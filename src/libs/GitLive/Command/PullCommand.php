<?php
/**
 *
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
 * @since      2018/11/24
 */

namespace GitLive\Command;


use App;
use GitLive\Application\Container;
use GitLive\Driver\ConfigDriver;
use GitLive\Driver\FetchDriver;
use GitLive\GitCmdExecuter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PullCommand extends CommandBase
{

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('pull')
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Pull from the appropriate remote repository.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Pull from the appropriate remote repository.'));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);


        $FetchDriver = App::make(FetchDriver::class);
        $ConfigDriver = App::make(ConfigDriver::class);
        $branch = $FetchDriver->getSelfBranchRef();
        $remote = 'origin';

        switch ((string)$branch) {
            case 'refs/heads' . $ConfigDriver->develop():
            case 'refs/heads' . $ConfigDriver->master():
                $remote = 'upstream';
                break;
            default:
                if (strpos($branch, 'refs/heads' . $ConfigDriver->releasePrefix()) !== false || strpos($branch, 'refs/heads' . $ConfigDriver->hotfixPrefix()) !== false) {
                    $remote = 'upstream';
                }

                break;
        }

        App::make(GitCmdExecuter::class)->pull($remote, $branch);
    }
}