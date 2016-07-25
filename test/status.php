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
        case strpos($stdin, '[OK]') !== false:
        case strpos($stdin, 'testing only :') !== false:
        case strpos($stdin, 'assertions test end') !== false:
        case strpos($stdin, 'memory usage') !== false:
            echo $stdin;
        break;
        default:
            fwrite(STDERR, $stdin);
            trigger_error($stdin, E_USER_ERROR);
        break;
    }
}
