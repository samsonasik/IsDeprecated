<?php

function foo()
{
    trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
    echo 'foo' . PHP_EOL;
}

function missPlacedDeprecated()
{
    E_USER_DEPRECATED;
    trigger_error('', E_USER_DEPRECATED);

    echo 'bar' . PHP_EOL;
}