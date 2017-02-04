<?php

class AclassWithNotDeprecatedFunctions
{
    function foo()
    {
        return 'foo' . PHP_EOL;
    }

    function foo2($parameter1, $parameter2)
    {
        return 'foo2' . PHP_EOL;
    }
}
