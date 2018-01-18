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

- [x] Detect on independent function  (E_USER_DEPRECATED)
- [x] Detect on function inside class (E_USER_DEPRECATED)
- [x] Detect on core php function     (E_DEPRECATED)

Installation
------------

Require uses [composer](https://getcomposer.org/):

```sh
composer require samsonasik/is-deprecated
```

Usage
-----

There are 2 functions:

*1. For user defined function*

```php
/**
 * @param  string|array $function the "functionName" or ["ClassName" or object, "functionName"]
 * @return bool
 */
function isDeprecatedUser($function): bool
```

*2. For core PHP function*

```php
/**
 * @param  callable $function callable function
 * @return bool
 */
function isDeprecatedCore(callable $function): bool
```

**Example On independent function**

```php
include 'vendor/autoload.php'; // autoload may already handled by your framework

use function IsDeprecated\isDeprecatedUser;

function foo()
{
    trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
    echo 'foo' . PHP_EOL;
}

function foonotdeprecated()
{
    echo 'foo' . PHP_EOL;
}

// deprecated
var_dump(isDeprecatedUser('foo')); // true

// not deprecated
var_dump(isDeprecatedUser('foonotdeprecated')); // false

// Usage Example:
if (isDeprecatedUser('foo')) {
    foonotdeprecated();;
} else {
    foo();
}
```

**Example On function inside class**

```php
include 'vendor/autoload.php'; // autoload may already handled by your framework

use function IsDeprecated\isDeprecatedUser;

class Aclass
{
    function foo()
    {
        trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
        echo 'foo' . PHP_EOL;
    }

    function foonotdeprecated()
    {
        echo 'foo' . PHP_EOL;
    }

    // you may call inside the class
    // with $this as first index of array parameter
    function execute()
    {
        if (isDeprecatedUser([$this, 'foo'])) {
            $this->foonotdeprecated();
            return;
        }

        $this->foo();
    }
}

// deprecated
var_dump(isDeprecatedUser(['Aclass', 'foo'])); // true OR
var_dump(isDeprecatedUser([new \Aclass(), 'foo'])); // true

// not deprecated
var_dump(isDeprecatedUser(['Aclass', 'foonotdeprecated'])); // false OR
var_dump(isDeprecatedUser([new \Aclass, 'foonotdeprecated'])); // false

// Usage Example:
if (isDeprecatedUser(['Aclass', 'foo'])) {
    (new \Aclass())->foonotdeprecated();;
} else {
    (new \Aclass())->foo();;
}
```

**Example On core PHP function**

```php
include 'vendor/autoload.php'; // autoload may already handled by your framework

use function IsDeprecated\isDeprecatedCore;

$function = function () {
    mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
};

//on php 7.1
var_dump(isDeprecatedCore($function)); // true

//on php 7.0
var_dump(isDeprecatedCore($function)); // false

// Usage Example:
if (isDeprecatedCore($function)) {
    // alternative function, eg: openssl ...
} else {
    $function();
}
```

Limitation
----------

For Core PHP Functions, the function passed actually already called. It ensure that we don't get error during call deprecated function, and we can use alternative function if the `isDeprecatedCore()` returns true.

Contributing
------------
Contributions are very welcome. Please read [CONTRIBUTING.md](https://github.com/samsonasik/IsDeprecated/blob/master/CONTRIBUTING.md)
