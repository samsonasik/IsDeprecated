<?php

namespace IsDeprecated;

use ErrorException;
use Zend\Stdlib\ErrorHandler;

/**
 * @param  callable    $function     callable function
 * @return bool
 */
function isDeprecated(callable $function): bool
{
    ob_start();
    ErrorHandler::start(E_USER_DEPRECATED|E_DEPRECATED);
    $function();
    $result = ErrorHandler::stop();
    ob_clean();

    if ($result instanceof ErrorException) {
        return true;
    }

    return false;
}
