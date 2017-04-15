<?php
if (!ini_get('date.timezone')) {
    $TZ = @date_default_timezone_get();
    date_default_timezone_set($TZ ? $TZ : 'Europe/London');
}

foreach (file($argv[1]) as $stdin) {
    if (!trim($stdin)) {
        continue;
    }
    switch (true) {
        case strpos($stdin, '[NG]') !== false:
        case strpos($stdin, '[ERROR]') !== false:
            fwrite(STDERR, $stdin);
            trigger_error($stdin, E_USER_ERROR);
        break;
        default:
            echo $stdin;
        break;
    }
}
