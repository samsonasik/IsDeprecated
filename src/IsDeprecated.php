<?php

namespace IsDeprecated;

use ErrorException;
use InvalidArgumentException;
use FunctionParser\FunctionParser;
use Zend\Stdlib\ErrorHandler;

/**
 * @param  string|array $function the "functionName" or ["ClassName", "functionName"]
 * @return bool
 */
function isDeprecatedUser($function): bool
{
    $parser    = FunctionParser::fromCallable($function);
    $tokenizer = $parser->getTokenizer();

    return $tokenizer->hasToken('E_USER_DEPRECATED');
}

/**
 * @param  callable $function callable function
 * @return bool
 */
function isDeprecatedCore(callable $function): bool
{
    ob_start();
    ErrorHandler::start(E_DEPRECATED);
    $function();
    $result = ErrorHandler::stop();
    ob_clean();

    if ($result instanceof ErrorException) {
        return true;
    }

    return false;
}
