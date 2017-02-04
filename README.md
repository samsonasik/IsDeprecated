IsDeprecated
============

[![Latest Version](https://img.shields.io/github/release/samsonasik/IsDeprecated.svg?style=flat-square)](https://github.com/samsonasik/IsDeprecated/releases)
[![Build Status](https://travis-ci.org/samsonasik/IsDeprecated.svg?branch=master)](https://travis-ci.org/samsonasik/IsDeprecated)
[![Coverage Status](https://coveralls.io/repos/github/samsonasik/IsDeprecated/badge.svg?branch=master)](https://coveralls.io/github/samsonasik/IsDeprecated?branch=master)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)
[![Downloads](https://img.shields.io/packagist/dt/samsonasik/is-deprecated.svg?style=flat-square)](https://packagist.org/packages/samsonasik/is-deprecated)

Introduction
------------

IsDeprecated is PHP7 Helper that can help you detect if your function is deprecated with E_USER_DEPRECATED and E_DEPRECATED level.

Features
--------

- [x] Detect on independent function level   (E_USER_DEPRECATED)
- [x] Detect on function inside object level (E_USER_DEPRECATED)
- [x] Detect on core php function            (E_DEPRECATED)

Installation
------------

Require uses [composer](https://getcomposer.org/):

```sh
composer require samsonasik/is-deprecated
```

Usage
-----

The usage is by follow its signature:

```php
/**
 * @param  string      $function     function name
 * @param  array       $parameters   array of function parameters
 * @param  object|null $object       object if function is called by object
 * @return bool
 */
isDeprecated(string $function, array $parameters = [], $object = null): bool
```

**Example On independent function level**

```php
include 'vendor/autoload.php'; // may already handled by your framework

use function IsDeprecated\isDeprecated;

function foo()
{
    trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
    echo 'foo' . PHP_EOL;
}

function foo2($parameter1, $parameter2)
{
    trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
    echo 'foo2' . PHP_EOL;
}

function foonotdeprecated()
{
    echo 'foo' . PHP_EOL;
}

function foo2notdeprecated($parameter1, $parameter2)
{
    echo 'foo2' . PHP_EOL;
}

// deprecated
var_dump(isDeprecated('foo'));                        // true
var_dump(isDeprecated('foo2', [1, 2]));                // true

// not deprecated
var_dump(isDeprecated('foonotdeprecated'));           // false
var_dump(isDeprecated('foo2notdeprecated', [1, 2]));  // false
```

**Example On function inside object level**

```php
include 'vendor/autoload.php'; // may already handled by your framework

use function IsDeprecated\isDeprecated;

class Aclass
{
    function foo()
    {
        trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
        echo 'foo' . PHP_EOL;
    }

    function foo2($parameter1, $parameter2)
    {
        trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
        echo 'foo2' . PHP_EOL;
    }

    function foonotdeprecated()
    {
        echo 'foo' . PHP_EOL;
    }

    function foo2notdeprecated($parameter1, $parameter2)
    {
        echo 'foo2' . PHP_EOL;
    }
}

$object = new Aclass();

// deprecated
var_dump(isDeprecated('foo', [], $object));                     // true
var_dump(isDeprecated('foo2', [1, 2], $object));                // true

// not deprecated
var_dump(isDeprecated('foonotdeprecated', [], $object));        // false
var_dump(isDeprecated('foo2notdeprecated', [1, 2], $object));   // false
```

**Example On core PHP function**

```php
//on php 7.0
var_dump(isDeprecated('mcrypt_get_iv_size', [MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC]));  // false

//on php 7.1
var_dump(isDeprecated('mcrypt_get_iv_size', [MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC]));  // true
```

Limitation
----------

Function actually already called. It currently ensure that we don't get error during call deprecated function, and we can use alternative function if the `isDeprecated()` returns true.

Contributing
------------
Contributions are very welcome. Please read [CONTRIBUTING.md](https://github.com/samsonasik/IsDeprecated/blob/master/CONTRIBUTING.md)
