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

/**
 * Class InteractiveShell
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
 * @since      2018-12-16
 */
class InteractiveShell extends GitBase implements InteractiveShellInterface
{
    /**
     * @var Envelopment
     */
    protected Envelopment $envelopment;

    /**
     * InteractiveShell constructor.
     * @param Envelopment $envelopment
     */
    public function __construct(Envelopment $envelopment)
    {
        $this->envelopment = $envelopment;
    }

    /**
     *  対話シェル
     *
     * @access      public
     * @param array|string $shell_message
     * @param bool|string $using_default OPTIONAL:false
     * @return string
     * @codeCoverageIgnore
     */
    public function interactiveShell($shell_message, $using_default = false): string
    {
        if (is_array($shell_message)) {
            $shell_message = implode("\n", $shell_message);
        }

        $shell_message .= "\n";
        while (true) {
            $this->echo($shell_message);
            $this->echo(':');
            $res = trim((string)fgets(STDIN, 1000));
            if ($res === '') {
                if ($using_default === false) {
                    continue;
                }
                $res = $using_default;
            }

            break;
        }

        return $res;
    }

    /**
     * @param string $text
     */
    public function echo($text): void
    {
        if ($this->envelopment->isWin()) {
            $text = mb_convert_encoding($text, 'SJIS-win', 'utf8');
        }

        echo $text;
    }
}
