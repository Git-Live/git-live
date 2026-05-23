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

use GitLive\Application\Container;
use GitLive\Application\Facade as App;
use GitLive\Driver\ConfigDriver;
use GitLive\Driver\FetchDriver;
use GitLive\Support\GitCmdExecutor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[\Symfony\Component\Console\Attribute\AsCommand(name: 'push')]
class PushCommand extends CommandBase
{
    /**
     * {@inheritdoc}
     * @throws \ErrorException
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setDescription(__('Push from the appropriate remote repository.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE
            )
            ->setHelp(resource()->help($this->getName(), $this->getDescription()));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \ErrorException
     * @throws \GitLive\Driver\Exception
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        $FetchDriver = App::make(FetchDriver::class);
        $ConfigDriver = App::make(ConfigDriver::class);
        $branch = $FetchDriver->getSelfBranchRef();
        $remote = 'origin';

        if (strpos($branch, 'refs/heads' . $ConfigDriver->releasePrefix()) !== false || strpos($branch, 'refs/heads' . $ConfigDriver->hotfixPrefix()) !== false) {
            $remote = 'upstream';
        }

        $option = [];

        if ($input->getOption('force')) {
            $option[] = '-f';
        }

        App::make(GitCmdExecutor::class)->push($remote, $branch, $option);

        return Command::SUCCESS;
    }
}
