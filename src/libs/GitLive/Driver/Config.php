<?php
/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
namespace GitLive\Driver;

/**
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
class Config extends DriverBase
{
    public function getParameter($key)
    {
        if (!$this->isGitRepository()) {
            return null;
        }
        return $this->GitCmdExecuter->config(array('--get', 'gitlive.'.$key));
    }

    public function setGlobalParameter($key, $value)
    {
        if (!$this->isGitRepository()) {
            return null;
        }
        return $this->GitCmdExecuter->config(array('--global', 'gitlive.'.$key, '"'.$value.'"'));
    }

    public function setLocalParameter($key, $value)
    {
        if (!$this->isGitRepository()) {
            return null;
        }
        return $this->GitCmdExecuter->config(array('--local', 'gitlive.'.$key, '"'.$value.'"'));
    }
}
