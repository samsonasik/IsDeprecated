<?php

use function IsDeprecated\isDeprecatedUser;

class ClassWithIsDeprecatedCall
{
    function deprecated()
    {
        trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
        echo 'bar' . PHP_EOL;
    }

    function notDeprecated()
    {
        echo 'bar';
    }

    function bar()
    {
        if (isDeprecatedUser([$this, 'deprecated'])) {
            $this->notDeprecated();
        }
    }
}
