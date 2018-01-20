<?php

use function IsDeprecated\isDeprecatedUser;

class ClassWithIsDeprecatedCall
{
    function deprecated()
    {
        trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
        echo 'bar' . PHP_EOL;
    }

    function deprecatedWithCheckBeforeTrigger()
    {
        if (1 === 1) {
            if (2 === 2) {
                if (3 === 3) {
                    trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
                }
            }
        }

        echo 'bar' . PHP_EOL;
    }

    function deprecatedWithCheckBeforeTrigger2()
    {
        $a = 1;
        switch ($a) {
            case 1:
                trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
                break;
        }
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

    function bar2()
    {
        if (isDeprecatedUser([$this, 'deprecatedWithCheckBeforeTrigger'])) {
            $this->notDeprecated();
        }
    }

    function bar3()
    {
        if (isDeprecatedUser([$this, 'deprecatedWithCheckBeforeTrigger2'])) {
            $this->notDeprecated();
        }
    }
}
