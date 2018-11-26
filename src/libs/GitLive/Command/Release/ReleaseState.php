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

namespace GitLive\Command\Release;


use App;
use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Driver\ReleaseDriver;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReleaseState extends CommandBase
{

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('release:state')
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
     * @return int|null|void
     * @throws \ReflectionException
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