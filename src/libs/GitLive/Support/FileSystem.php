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

namespace GitLive\Support;

use GitLive\GitBase;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FileSystem
 *
 * @category   GitCommand
 * @package    GitLive\Support
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
class FileSystem extends GitBase implements FileSystemInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * FileSystem constructor.
     * @param OutputInterface $output
     */
    public function __construct($output = null)
    {
        dump($output);
        $this->output = $output;
    }

    /**
     * @param string $url
     * @param bool   $use_include_path
     * @param null   $context
     * @return false|string
     */
    public function getContents($url, $use_include_path = false, $context = null)
    {
        return file_get_contents($url, $use_include_path, $context);
    }

    /**
     * @param string $url
     * @return string
     */
    public function getContentsWithProgress($url)
    {
        $ctx = stream_context_create(
            ['http' => ['ignore_errors' => true]]
        );
        $progressBar = null;
        $bite_diff = 0;
        stream_context_set_params(
            $ctx,
            ['notification' =>
                function ($notification_code, $severity, $message, $message_code, $bytes_transferred, $bytes_max) use (&$progressBar, &$bite_diff) {
                    switch ($notification_code) {
                        case STREAM_NOTIFY_RESOLVE:
                        case STREAM_NOTIFY_AUTH_REQUIRED:
                        case STREAM_NOTIFY_FAILURE:
                        case STREAM_NOTIFY_AUTH_RESULT:
                        case STREAM_NOTIFY_COMPLETED:
                            $this->output(var_export(compact('notification_code', 'severity', 'message', 'message_code', 'bytes_transferred', 'bytes_max'), true));
                            // 無視
                            break;
                        case STREAM_NOTIFY_REDIRECTED:
                            $this->output(__('Being redirected to'), $message);

                            break;
                        case STREAM_NOTIFY_CONNECT:
                            $this->output(__('Connected...'));

                            break;
                        case STREAM_NOTIFY_FILE_SIZE_IS:
                            $this->output(__('Got the file size'), $bytes_max);

                            if ($this->output) {
                                $progressBar = new ProgressBar($this->output, $bytes_max);
                            }

                            break;
                        case STREAM_NOTIFY_MIME_TYPE_IS:
                            $this->output(__('Found the mime-type'), $message);

                            break;
                        case STREAM_NOTIFY_PROGRESS:
                            if (isset($progressBar)) {
                                $progressBar->advance($bytes_transferred - $bite_diff);
                                $bite_diff = $bytes_transferred;
                            } else {
                                $this->output(__('Made some progress, downloaded'), $bytes_transferred);
                            }

                            break;
                    }
                    echo "\n";
                },
            ]
        );

        $res = $this->getContents($url, false, $ctx);

        /**
         * @var ProgressBar $progressBar
         */
        if ($progressBar) {
            $progressBar->finish();
        }

        return $res;
    }

    public function output($message, $value = null)
    {
        if (!$this->output) {
            echo $message . ($value ? ' : ' . $value : '') . "\n";
        } elseif ($value) {
            $this->output->writeln("{$message} : <info>{$value}</info>\n");
        } else {
            $this->output->writeln($message);
        }
    }

    /**
     * @param string $url
     * @param mixed  $content
     * @param int    $flags
     * @param null   $context
     * @return bool|int
     */
    public function putContents($url, $content, $flags = 0, $context = null)
    {
        return file_put_contents($url, $content, $flags, $context);
    }
}
