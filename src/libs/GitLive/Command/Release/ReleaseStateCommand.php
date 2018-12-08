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

namespace GitLive\Command\Release;

use App;
use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Driver\ReleaseDriver;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReleaseStateCommand extends CommandBase
{
    protected static $signature_name = 'release:state';

    protected function configure()
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Check the status of release.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Check the status of release.'))
            ->addOption('ck_only', 'd', InputOption::VALUE_NONE, __('Check only.'))
            ->addOption('with_merge_commit', 'r', InputOption::VALUE_NONE, __('With merge commit.'));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \ReflectionException
     * @return null|int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        $res = App::make(ReleaseDriver::class)->buildState(
            $input->getOption('ck_only'),
            $input->getOption('with_merge_commit')
        );

        $output->writeln($res);
    }
}
