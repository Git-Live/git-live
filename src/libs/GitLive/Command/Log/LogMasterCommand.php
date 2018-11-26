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

namespace GitLive\Driver\Log;


use App;
use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Driver\LogDriver;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LogMasterCommand extends CommandBase
{

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('log:master')
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Show diff upstream master branch.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Show diff upstream master branch.'));
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

        $output->writeln(
            App::make(LogDriver::class)->logMaster()
        );

    }
}