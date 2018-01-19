<?php

function another_trigger_error()
{
    trigger_error('Error message', E_USER_ERROR); // first E_USER_ERROR token already cached first
    trigger_error('Error message', E_DEPRECATED);
}