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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SelfUpdateCommand extends CommandBase
{
    protected static $signature_name = 'self-update';

    protected function configure()
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Update git-live command.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(resource()->help(self::$signature_name, $this->getDescription()))
            ->addArgument('save_path', InputArgument::OPTIONAL, __('Save path.'))
            ->addOption(
                'no-cache',
                'c',
                InputOption::VALUE_NONE,
                __('Get a master phar.')
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        if (GIT_LIVE_VERSION === 'cli' && !$input->getArgument('save_path')) {
            return 1;
        }

        $url = 'https://raw.githubusercontent.com/Git-Live/git-live/v3.0/bin/git-live.phar';
        if ($input->getOption('no-cache')) {
            $url = 'https://github.com/Git-Live/git-live/raw/v3.0/bin/git-live.phar';
        }

        $FileSystem = App::make(FileSystem::class);

        $FileSystem->putContents($input->getArgument('save_path') ?: GIT_LIVE_INSTALL_PATH, $FileSystem->getContentsWithProgress($url));

        return 0;
    }
}
