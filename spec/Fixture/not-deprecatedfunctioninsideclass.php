<?php

class AclassWithNotDeprecatedFunctions
{
    function foo()
    {
        echo 'foo' . PHP_EOL;
    }

    function foo2($parameter1, $parameter2)
    {
        echo 'foo2' . PHP_EOL;
    }
}
