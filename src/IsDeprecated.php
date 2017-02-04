<?php

namespace IsDeprecated;

use ErrorException;
use Zend\Stdlib\ErrorHandler;

function isDeprecated(string $function, array $parameters = [], $object = null)
{
    ErrorHandler::start(E_USER_DEPRECATED);
    if (is_object($object)) {
        $object->$function(...$parameters);
    }
    if ($object === null) {
        $function(...$parameters);
    }
    $result = ErrorHandler::stop();

    if ($result instanceof ErrorException) {
        return true;
    }

    return false;
}
