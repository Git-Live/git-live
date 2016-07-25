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
namespace {
function GitLiveMain()
{
    static $instance;
    if ($instance) {
        return $instance;
    }
    $instance = \EnviMockLight::mock('\GitLive\Mock\GitLive\Main', array(), false);
    return $instance;
}
}
namespace GitLive\Main{

    function ini_get()
    {
        $function_name = substr(__FUNCTION__, strlen(__NAMESPACE__) + 1);
        call_user_func_array(array(GitLiveMain(), $function_name), func_get_args());
    }

    function date_default_timezone_get()
    {
        $function_name = substr(__FUNCTION__, strlen(__NAMESPACE__) + 1);
        call_user_func_array(array(GitLiveMain(), $function_name), func_get_args());
    }

    function date_default_timezone_set()
    {
        $function_name = substr(__FUNCTION__, strlen(__NAMESPACE__) + 1);
        call_user_func_array(array(GitLiveMain(), $function_name), func_get_args());
    }


    function function_exists()
    {
        $function_name = substr(__FUNCTION__, strlen(__NAMESPACE__) + 1);
        call_user_func_array(array(GitLiveMain(), $function_name), func_get_args());
    }

    function textdomain()
    {
        $function_name = substr(__FUNCTION__, strlen(__NAMESPACE__) + 1);
        call_user_func_array(array(GitLiveMain(), $function_name), func_get_args());
    }

    function bind_textdomain_codeset()
    {
        $function_name = substr(__FUNCTION__, strlen(__NAMESPACE__) + 1);
        call_user_func_array(array(GitLiveMain(), $function_name), func_get_args());
    }

    function setlocale()
    {
        $function_name = substr(__FUNCTION__, strlen(__NAMESPACE__) + 1);
        call_user_func_array(array(GitLiveMain(), $function_name), func_get_args());
    }

}
