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
class FileSystem implements FileSystemInterface
{
    /**
     * @param string $url
     * @return string
     */
    public function getContents($url)
    {
        return file_get_contents($url);
    }

    /**
     * @param string $url
     * @return string
     */
    public function getContentsWithProgress($url)
    {
        $ctx = stream_context_create();
        stream_context_set_params(
            $ctx,
            ['notification' =>
                function ($notification_code, $severity, $message, $message_code, $bytes_transferred, $bytes_max) {
                    switch ($notification_code) {
                        case STREAM_NOTIFY_RESOLVE:
                        case STREAM_NOTIFY_AUTH_REQUIRED:
                        case STREAM_NOTIFY_COMPLETED:
                        case STREAM_NOTIFY_FAILURE:
                        case STREAM_NOTIFY_AUTH_RESULT:
                            var_dump($notification_code, $severity, $message, $message_code, $bytes_transferred, $bytes_max);
                            // 無視
                            break;
                        case STREAM_NOTIFY_REDIRECTED:
                            echo 'Being redirected to: ', $message;

                            break;
                        case STREAM_NOTIFY_CONNECT:
                            echo 'Connected...';

                            break;
                        case STREAM_NOTIFY_FILE_SIZE_IS:
                            echo 'Got the filesize: ', $bytes_max;

                            break;
                        case STREAM_NOTIFY_MIME_TYPE_IS:
                            echo 'Found the mime-type: ', $message;

                            break;
                        case STREAM_NOTIFY_PROGRESS:
                            echo 'Made some progress, downloaded ', $bytes_transferred, ' so far';

                            break;
                    }
                    echo "\n";
                },
            ]
        );

        return file_get_contents($url, false, $ctx);
    }

    /**
     * @param string $url
     * @param mixed  $content
     * @return int
     */
    public function putContents($url, $content)
    {
        return file_put_contents($url, $content);
    }
}
