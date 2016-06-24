<?php
while($stdin = fgets(STDIN))
{
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
            fwrite(STDERR, 'error');
            fwrite(STDERR, $stdin);
        break;
    }

}