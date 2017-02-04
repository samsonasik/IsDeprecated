<?php

namespace IsDeprecated;

use ErrorException;
use Zend\Stdlib\ErrorHandler;

function isDeprecated(string $function, array $parameters = [], $object = null): bool
{
    ob_start();
    ErrorHandler::start(E_USER_DEPRECATED);
    if (is_object($object)) {
        $object->$function(...$parameters);
    }
    if ($object === null) {
        $function(...$parameters);
    }
    $result = ErrorHandler::stop();
    ob_clean();

    if ($result instanceof ErrorException) {
        return true;
    }

    return false;
}
