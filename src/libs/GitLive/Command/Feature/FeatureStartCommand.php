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

namespace GitLive\Command\Feature;


use App;
use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Driver\FeatureDriver;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FeatureStartCommand
 *
 * @category   GitCommand
 * @package    GitLive\Command\Feature
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
class FeatureStartCommand extends CommandBase
{

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('feature:start')
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Create a new feature branch.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Create a new feature branch.'))
            ->addArgument('branch_name', InputArgument::REQUIRED, 'feature name');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        $FeatureDriver = App::make(FeatureDriver::class);

        $FeatureDriver->featureStart($input->getArgument('branch_name'));
    }
}