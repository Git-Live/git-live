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
use GitLive\Support\FileSystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SelfUpdateCommand extends CommandBase
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('self-update')
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Update git-live command.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Update git-live command.'))
            ->addOption(
                'no-cache',
                'c',
                InputOption::VALUE_NONE,
                'Get a master phar.'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \ReflectionException
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        if (GIT_LIVE_VERSION === 'cli') {
            return 1;
        }

        $url = 'https://raw.githubusercontent.com/Git-Live/git-live/master/bin/git-live.phar';
        if ($input->getOption('no-cache')) {
            $url = 'https://github.com/Git-Live/git-live/raw/master/bin/git-live.phar';
        }

        $FileSystem = App::make(FileSystem::class);

        $FileSystem->putContents(GIT_LIVE_INSTALL_PATH, $FileSystem->getContentsWithProgress($url));

        return 0;
    }
}
