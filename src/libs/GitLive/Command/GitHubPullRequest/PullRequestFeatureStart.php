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

namespace GitLive\Command\GitHubPullRequest;

use GitLive\Application\Container;
use GitLive\Application\Facade as App;
use GitLive\Command\CommandBase;
use GitLive\Driver\PullRequestDriver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PullRequestFeatureStart
 *
 * @category   GitCommand
 * @package    GitLive\Command\GitHubPullRequest
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018/11/26
 */
class PullRequestFeatureStart extends CommandBase
{
    protected static $defaultName = 'pr:feature:start';

    /**
     * {@inheritdoc}
     * @throws \ErrorException
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Feature start and merge pull request.'))
            // the full command description shown when running the command with
            // the "--help" Merge
            ->setHelp(resource()->help(self::$defaultName, $this->getDescription()))
            ->addArgument('pull_request_number', InputArgument::REQUIRED, 'Pull request id')
            ->addArgument('feature_name', InputArgument::REQUIRED, 'feature_name')
            ->addOption(
                'soft',
                null,
                InputOption::VALUE_NONE,
                'Do not merge develop branch.'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \GitLive\Driver\Exception
     * @throws \GitLive\Exception
     * @throws \ErrorException
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        if ($input->getOption('soft')) {
            App::make(PullRequestDriver::class)->featureStartSoft(
                $input->getArgument('pull_request_number'),
                $input->getArgument('feature_name')
            );

            return Command::SUCCESS;
        }
        App::make(PullRequestDriver::class)->featureStart(
            $input->getArgument('pull_request_number'),
            $input->getArgument('feature_name')
        );

        return Command::SUCCESS;
    }
}
