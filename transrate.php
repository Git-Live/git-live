<?php
/**
 * transrate.php
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
 * @since      2018-12-11
 */

require __DIR__ . '/vendor/autoload.php';

use Gettext\Translations;

foreach (glob(__DIR__ . '/resources/lang/*/LC_MESSAGES/*po') as $po) {
    echo $po."\n";

//import from a .po file:
    $translations = Translations::fromPoFile($po);

//export to a php array:
    $translations->toPhpArrayFile($po . '.php');
}
