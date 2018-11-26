<?php
/**
 * CommandBase.php
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

namespace GitLive\Command;


use App;
use GitLive\Driver\ConfigDriver;
use GitLive\GitLive;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class CommandBase
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
 * @since      2018/11/23
 */
abstract class CommandBase extends Command
{

    public function updateChecker(OutputInterface $output)
    {
        if ($this->ckNewVersion()) {
            $output->writeln(__('Alert: An update to the Git Live is available. Run "git live self-update" to get the latest version.'));
        }
    }

    /**
     *  新しいVersionが出ていないか確認する
     *
     * @access      public
     * @return bool
     * @throws \ReflectionException
     */
    public function ckNewVersion()
    {
        $latest_version = $this->getLatestVersion();

        return (bool)version_compare(GitLive::VERSION, $latest_version, '<');
    }

    /**
     * 最終Versionを取得
     *
     * @access      public
     * @return string
     * @throws \ReflectionException
     */
    public function getLatestVersion()
    {
        static $latest_version;

        if ($latest_version) {
            return $latest_version;
        }

        /**
         * @var ConfigDriver $ConfigDriver
         */
        $ConfigDriver = $this->Driver('Config');
        $latest_version_fetch_time = (int)$ConfigDriver->getParameter('latestversion.fetchtime');


        $update_ck_span = (int)$ConfigDriver->getParameter('latestversion.update_ck_span') ?: GitLive::DEFAULT_UPDATE_CK_SPAN;


        if (!empty($latest_version_fetch_time) && (time() - $latest_version_fetch_time) < $update_ck_span) {
            return $latest_version = $ConfigDriver->getParameter('latestversion.val');
        }

        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: PHP',
                ],
            ],
        ];

        $context = stream_context_create($opts);
        $contents = file_get_contents(GitLive::VERSION_API, false, $context);
        if (!$contents) {
            $latest_version = GitLive::VERSION;

            return $latest_version;
        }

        $arr = json_decode($contents, true);
        if (substr($arr['tag_name'], 0, 1) === 'v') {
            $latest_version = substr($arr['tag_name'], 1);
        } else {
            $latest_version = $arr['tag_name'];
        }

        $ConfigDriver->setLocalParameter('latestversion.fetchtime', time());
        $ConfigDriver->setLocalParameter('latestversion.val', $latest_version);

        return $latest_version;
    }

    /**
     *
     *
     * @access      public
     * @param  string $driver_name
     * @return \GitLive\Driver\DriverBase
     * @throws \ReflectionException
     * @codeCoverageIgnore
     */
    public function Driver($driver_name)
    {
        return App::make('\GitLive\Driver\\' . $driver_name . 'Driver');
    }

}