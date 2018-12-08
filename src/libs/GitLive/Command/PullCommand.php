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

namespace GitLive\Command;

use App;
use GitLive\Application\Container;
use GitLive\Driver\ConfigDriver;
use GitLive\Driver\Exception;
use GitLive\Driver\FetchDriver;
use GitLive\Driver\ResetDriver;
use GitLive\GitCmdExecutor;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PullCommand extends CommandBase
{
    protected static $signature_name = 'pull';

    protected function configure()
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Pull from the appropriate remote repository.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Pull from the appropriate remote repository.'))
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE
            )
            ->addArgument('remote', InputArgument::OPTIONAL, 'Remote name[origin upstream deploy]', null)
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @return null|int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        $FetchDriver = App::make(FetchDriver::class);
        $ConfigDriver = App::make(ConfigDriver::class);

        $branch = $FetchDriver->getSelfBranchRef();

        $remote = $input->getArgument('remote');

        if (empty($remote)) {
            $remote = 'origin';
            switch ((string)$branch) {
                case 'refs/heads/' . $ConfigDriver->develop():
                case 'refs/heads/' . $ConfigDriver->master():
                    $remote = 'upstream';

                    break;
                default:
                    if (strpos($branch, 'refs/heads' . $ConfigDriver->releasePrefix()) !== false || strpos($branch, 'refs/heads' . $ConfigDriver->hotfixPrefix()) !== false) {
                        $remote = 'upstream';
                    }

                    break;
            }
        }

        if ($input->getOption('force')) {
            App::make(ResetDriver::class)->forcePull($remote);
        } else {
            switch ($remote) {
                case 'upstream':
                    App::make(GitCmdExecutor::class)->pull($remote, $branch);

                    break;
                case 'deploy':
                    App::make(GitCmdExecutor::class)->pull($ConfigDriver->deployRemote(), $branch);

                    break;
                case 'origin':
                    App::make(GitCmdExecutor::class)->pull($remote, $branch);

                    break;
                default:
                    throw new Exception(__('Undefined remote option : ') . $remote . "\n" . ' You can use origin upstream deploy');
            }
        }
    }
}
