<?php

class Aclass
{
    function foo()
    {
        trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
        echo 'foo' . PHP_EOL;
    }

    function foo2($parameter1, $parameter2)
    {
        trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
        echo 'foo2' . PHP_EOL;
    }
}
