<?php
/**
 * Feature.php
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
 * @since      2018/11/24
 */

namespace GitLive\Command;


use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LogCommand
 *
 * @category   GitCommand
 * @package    GitLive\Command
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018/11/24
 */
class LogCommand extends CommandBase
{

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('log')
            // the short description shown while running "php bin/console list"
            ->setDescription(__('log control'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Log control'))
            ->addArgument(
                'task',
                InputArgument::REQUIRED,
                'git live log task'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        switch ($input->getArgument('task')) {
            case 'develop':
                $command = $this->getApplication()->find('log:develop');

                $arguments = [
                ];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
                break;
            case 'master':
                $command = $this->getApplication()->find('log:master');

                $arguments = [];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
                break;

        }


        return 0;

    }
}