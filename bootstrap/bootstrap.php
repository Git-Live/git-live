<?php
/**
 * bootstrap.php
 *
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
 * @since      2018/11/23
 */


ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);

if (!ini_get('date.timezone')) {
    $TZ = @date_default_timezone_get();
    date_default_timezone_set($TZ ? $TZ : 'Europe/London');
}


if (!defined('GIT_LIVE_VERSION')) {
    define('GIT_LIVE_VERSION', 'cli');
}

define('RESOURCES_DIR', dirname(__DIR__).DIRECTORY_SEPARATOR.'resources');

class_alias(\GitLive\Application\Facade::class, 'App');

// get-textが有効かどうかで処理を分ける
if (!function_exists('\textdomain')) {
    define('GIT_LIVE_IS_GET_TEXT', false);
} else {
    // domain
    $domain = 'messages';

    // LANG
    $locale = trim(`echo \$LANG`);
    if (empty($locale)) {
        $locale = 'ja_JP.UTF-8';
    }

    setlocale(LC_ALL, $locale);

    list($lang, $code_set) = explode('.', $locale);
    textdomain($domain);
    bind_textdomain_codeset($domain, 'UTF-8');


    define('GIT_LIVE_BINDTEXTDOMAIN',  RESOURCES_DIR.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR);

    $is_bindtextdomain = bindtextdomain($domain, GIT_LIVE_BINDTEXTDOMAIN);

    if ($is_bindtextdomain && is_dir(GIT_LIVE_BINDTEXTDOMAIN)) {
        define('GIT_LIVE_IS_GET_TEXT', true);
    } else {
        define('GIT_LIVE_IS_GET_TEXT', false);
    }
}
