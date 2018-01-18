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
 * @param  callable    $function     callable function
 * @return bool
 */
isDeprecated(callable $function): bool
```

**Example On independent function level**

```php
include 'vendor/autoload.php'; // autoload may already handled by your framework

use function IsDeprecated\isDeprecated;

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
var_dump(
    isDeprecated(function () {
        foo();
    })
);                        // true

// not deprecated
var_dump(
    isDeprecated(function () {
        foonotdeprecated();
    })
);                        // false

// Usage Example:
$function = function () {
    foo();
};
if (isDeprecated($function)) {
    foonotdeprecated();;
} else {
    $function();
}
```

**Example On function inside object level**

```php
include 'vendor/autoload.php'; // autoload may already handled by your framework

use function IsDeprecated\isDeprecated;

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
}

$object = new Aclass();

// deprecated
var_dump(
    isDeprecated(function () {
        (new \Aclass())->foo();
    })
);                        // true

// not deprecated
var_dump(
    isDeprecated(function () {
        (new \Aclass())->foonotdeprecated();
    })
);                        // false

// Usage Example:
$function = function () {
    (new \Aclass())->foo();
};
if (isDeprecated($function)) {
    (new \Aclass())->foonotdeprecated();;
} else {
    $function();
}
```

**Example On core PHP function**

```php
include 'vendor/autoload.php'; // autoload may already handled by your framework

use function IsDeprecated\isDeprecated;

//on php 7.1
var_dump(
    isDeprecated(function () {
        mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    })
);  // true

//on php 7.0
var_dump(
    isDeprecated(function () {
        mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    })
);  // false

// Usage Example:
$function = function () {
    mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
};
if (isDeprecated($function)) {
    // alternative function, eg: openssl ...
} else {
    $function();
}
```

Limitation
----------

Function actually already called. It currently ensure that we don't get error during call deprecated function, and we can use alternative function if the `isDeprecated()` returns true.

Contributing
------------
Contributions are very welcome. Please read [CONTRIBUTING.md](https://github.com/samsonasik/IsDeprecated/blob/master/CONTRIBUTING.md)
