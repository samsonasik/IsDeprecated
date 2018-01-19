<?php

namespace IsDeprecated;

use ErrorException;
use Exception;
use FunctionParser\FunctionParser;
use Zend\Stdlib\ErrorHandler;

/**
 * @param  string|array $function the "functionName" or ["ClassName" or object, "functionName"]
 * @return bool
 */
function isDeprecatedUser($function): bool
{
    $parser    = FunctionParser::fromCallable($function);
    $tokenizer = $parser->getTokenizer();
    if (! $index = $tokenizer->findToken('trigger_error')) {
        return false;
    }

    $indexNext = 4;
    do {
        try {
            $tokenizerWithRange = $tokenizer->getTokenRange($index + $indexNext, 3);
            $found = $tokenizerWithRange->current()->code === 'E_USER_DEPRECATED';
        } catch (Exception $e) {
            return false;
        }
    } while ($indexNext++ <=7 && ! $found);

    return $found;
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
