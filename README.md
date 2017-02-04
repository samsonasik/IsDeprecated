IsDeprecated
============

[![Latest Version](https://img.shields.io/github/release/samsonasik/IsDeprecated.svg?style=flat-square)](https://github.com/samsonasik/IsDeprecated/releases)
[![Build Status](https://travis-ci.org/samsonasik/IsDeprecated.svg?branch=master)](https://travis-ci.org/samsonasik/IsDeprecated)
[![Coverage Status](https://coveralls.io/repos/github/samsonasik/IsDeprecated/badge.svg?branch=master)](https://coveralls.io/github/samsonasik/IsDeprecated?branch=master)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)
[![Downloads](https://img.shields.io/packagist/dt/samsonasik/is-deprecated.svg?style=flat-square)](https://packagist.org/packages/samsonasik/is-deprecated)

Introduction
------------

IsDeprecated is PHP7 Helper that can help you detect if your function is deprecated with E_USER_DEPRECATED level.

Features
--------

- [x] Detect on independent function level
- [x] Detect on function inside object level

Installation
------------

**1. Require this module uses [composer](https://getcomposer.org/).**

```sh
composer require samsonasik/is-deprecated:dev-master
```

**2. Usage**

On independent function level
-----------------------------

```php
use function IsDeprecated\isDeprecated;

function foo()
{
    trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
    return 'foo' . PHP_EOL;
}

function foo2($parameter1, $parameter2)
{
    trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
    return 'foo2' . PHP_EOL;
}

var_dump(isDeprecated('foo'));          // true
var_dump(isDeprecated('foo', [1, 2]));  // true
```

On function inside object level
-------------------------------

```php
use function IsDeprecated\isDeprecated;

class Aclass
{
    function foo()
    {
        trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
        return 'foo' . PHP_EOL;
    }

    function foo2($parameter1, $parameter2)
    {
        trigger_error('this method has been deprecated.', E_USER_DEPRECATED);
        return 'foo2' . PHP_EOL;
    }
}

$object = new Aclass();
var_dump(isDeprecated('foo', [], $object));      // true
var_dump(isDeprecated('foo', [1, 2], $object));  // true
```

Contributing
------------
Contributions are very welcome. Please read [CONTRIBUTING.md](https://github.com/samsonasik/IsDeprecated/blob/master/CONTRIBUTING.md)
