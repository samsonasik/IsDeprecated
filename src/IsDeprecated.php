<?php

namespace IsDeprecated;

use ErrorException;
use InvalidArgumentException;
use FunctionParser\FunctionParser;
use Zend\Stdlib\ErrorHandler;

/**
 * @param  callable $function callable function
 * @return bool
 */
function isDeprecatedWithActualCall(callable $function)
{
    ob_start();
    ErrorHandler::start(E_USER_DEPRECATED|E_DEPRECATED);
    $function();
    $result = ErrorHandler::stop();
    ob_clean();

    return $result instanceof ErrorException;
}

/**
 * @param  string|array $function the "functionName" or ["ClassName" or object, "functionName"] or "ClassName::functionName"
 * @throws InvalidArgumentException when trigger_error found but the error is not E_USER_DEPRECATED
 * @throws InvalidArgumentException when trigger_error and E_USER_DEPRECATED found but misplaced
 * @return bool
 */
function isDeprecatedUser($function): bool
{
    $parser    = FunctionParser::fromCallable($function);
    $tokenizer = $parser->getTokenizer();
    if (! $indexTriggerError = $tokenizer->findToken('trigger_error')) {
        return false;
    }

    $indexEUserDeprecated = $tokenizer->findToken('E_USER_DEPRECATED');
    if (! $indexEUserDeprecated) {
        throw new InvalidArgumentException(
            sprintf(
                'function %s has trigger_error but not E_USER_DEPRECATED',
                $function
            )
        );
    }

    if ($indexEUserDeprecated < $indexTriggerError) {
        throw new InvalidArgumentException(
            sprintf(
                'function %s has trigger_error and E_USER_DEPRECATED token but misplaced',
                $function
            )
        );
    }

    $indexTIF       = $tokenizer->findToken('T_IF');
    $indexTSWITCH   = $tokenizer->findToken('T_SWITCH');
    $indexCondition = false;
    if ($indexTIF) {
        $indexCondition = $indexTIF;
    }
    if (!$indexTIF && $indexTSWITCH) {
        $indexCondition = $indexTSWITCH;
    }
    if ($indexCondition && $indexTriggerError > $indexCondition) {
        if (is_array($function)) {
            $class = $function[0];
            if (is_object($function[0])) {
                $class = get_class($function[0]);
            }
            $function = $class . '::' . $function[1];
        }
        throw new InvalidArgumentException(
            sprintf(
                'function %s has trigger_error and E_USER_DEPRECATED but has condition check before, use isDeprecatedWithActualCall() method instead',
                $function
            )
        );
    }

    return true;
}

/**
 * @param  callable $function callable function
 * @return bool
 */
function isDeprecatedCore(callable $function): bool
{
    return isDeprecatedWithActualCall($function);
}
