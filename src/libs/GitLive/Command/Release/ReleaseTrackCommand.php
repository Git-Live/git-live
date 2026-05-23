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

use GitLive\Application\Container;
use GitLive\Application\Facade as App;
use GitLive\Command\CommandBase;
use GitLive\Driver\ReleaseDriver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[\Symfony\Component\Console\Attribute\AsCommand(name: 'release:track')]
class ReleaseTrackCommand extends CommandBase
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
            ->setDescription(__('Support preparation of a new production release/.'))
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

        App::make(ReleaseDriver::class)->buildTrack();

        return Command::SUCCESS;
    }
}
