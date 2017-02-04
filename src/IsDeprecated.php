<?php

namespace IsDeprecated;

use ErrorException;
use Zend\Stdlib\ErrorHandler;

/**
 * @param  string      $function     function name
 * @param  array       $parameters   array of function parameters
 * @param  object|null $object       object if function is called by object
 * @return bool
 */
function isDeprecated(string $function, array $parameters = [], $object = null): bool
{
    ob_start();
    ErrorHandler::start(E_USER_DEPRECATED|E_DEPRECATED);
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
