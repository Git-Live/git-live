<?php
/**
 * ListCommand.php
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018/11/23
 */

namespace GitLive\Command\Feature;


use App;
use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Driver\FeatureDriver;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListCommand
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
 * @since      2018/11/23
 */
class ListCommand extends CommandBase
{

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('feature:list')
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Display features list.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Display features list.'));
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

        $res = $FeatureDriver->featureList();

        $output->writeln($res);

    }

}