<?php
/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveFlow
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
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', -1);
    if (!defined('GIT_LIVE_INSTALL_DIR')) {
        define('GIT_LIVE_INSTALL_DIR', __FILE__);
    }

    if (!defined('GIT_LIVE_VERSION')) {
        define('GIT_LIVE_VERSION', 'cli');
    }

    $is_debug = true;

    if (!class_exists('\GitLive\Autoloader', false)) {
        include 'libs/GitLive/Autoloader.php';
    }
}
namespace GitLive\Main{
    if (!ini_get('date.timezone')) {
        $TZ = @date_default_timezone_get();
        date_default_timezone_set($TZ ? $TZ : 'Europe/London');
    }

    // get-textが有効かどうかで処理を分ける
    $is_get_text = false;
    if (!function_exists('\_')) {
        include __DIR__.DIRECTORY_SEPARATOR.'get_text.php';
    } else {
        // LANG
        $locale = trim(`echo \$LANG`);
        if (empty($locale)) {
            $locale = 'ja_JP.UTF-8';
        }

        setlocale(LC_ALL, $locale);

        list($lang, $code_set) = explode('.', $locale);
        $domain                = substr($lang, 0, 2);
        textdomain($domain);
        bind_textdomain_codeset($domain, 'UTF-8');
        $is_get_text = true;
    }

    $Autoloader = new \GitLive\Autoloader;
    $Autoloader->register();

    if (GIT_LIVE_VERSION === 'phar') {
        $Autoloader->addNamespace('GitLive\Driver', 'phar://git-live.phar/libs/GitLive/Driver');
        $Autoloader->addNamespace('GitLive', 'phar://git-live.phar/libs/GitLive');
        if ($is_get_text) {
            bindtextdomain($domain, 'phar://git-live.phar/lang/');
        }
    } else {
        $Autoloader->addNamespace('GitLive\Driver', __DIR__.'/libs/GitLive/Driver');
        $Autoloader->addNamespace('GitLive', __DIR__.'/libs/GitLive');
        if ($is_get_text) {
            bindtextdomain($domain, 'phar://git-live.phar/lang/');
        }
    }

    try {
        $GitLive = new \GitLive\GitLive;

        if ($GitLive->isWin()) {
            mb_internal_encoding('utf8');
            mb_http_output('sjis-win');
            mb_http_input('sjis-win');
        }
        $GitLive->execute();
    } catch (\exception $e) {
        $GitLive->ncecho($e->getMessage()."\n");
    }
}
