<?php

function foo()
{
    trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
    echo 'foo' . PHP_EOL;
}
