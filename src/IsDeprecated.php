<?php

namespace IsDeprecated;

use ErrorException;
use Exception;
use FunctionParser\FunctionParser;
use Zend\Stdlib\ErrorHandler;

/**
 * @param  string|array $function the "functionName" or ["ClassName" or object, "functionName"]
 * @throws Exception when trigger_error found but the error is not E_USER_DEPRECATED
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
    $found     = false;
    do {
        try {
            $tokenizerWithRange = $tokenizer->getTokenRange($index + $indexNext, 3);
            $found = $tokenizerWithRange->current()->code === 'E_USER_DEPRECATED';
        } catch (Exception $e) {
            throw new Exception(
                sprintf(
                    'function %s has trigger_error but not E_USER_DEPRECATED',
                    $function
                )
            );
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
