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

use App;
use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Driver\PullRequestDriver;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PullRequestPull
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
class PullRequestPull extends CommandBase
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('pr:pull')
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Pull pull request locally.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Pull pull request locally.'));
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

        App::make(PullRequestDriver::class)->prPull();
    }
}
