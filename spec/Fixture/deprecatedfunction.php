<?php

function foo()
{
    trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
    echo 'foo' . PHP_EOL;
}

function deprecated_in_some_condition()
{
    if (1 === 1) {
        trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
    }
    echo 'foo' . PHP_EOL;
}

function deprecated_in_some_condition2()
{
    $a = 1;
    switch ($a) {
        case 1:
            trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
            break;
    }
    echo 'foo' . PHP_EOL;
}

function missPlacedDeprecated()
{
    E_USER_DEPRECATED;
    trigger_error('', E_USER_DEPRECATED);

    echo 'bar' . PHP_EOL;
}