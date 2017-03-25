<?php

namespace IsDeprecatedSpec;

use function IsDeprecated\isDeprecated;

describe('IsDeprecated', function () {

    describe('isDeprecated()', function () {

        context('independent function' , function () {

            beforeAll(function () {
                include __DIR__ . '/Fixture/deprecatedfunction.php';
                include __DIR__ . '/Fixture/not-deprecatedfunction.php';
            });

            context('deprecated' , function () {

                it('deprecated on without parameters', function () {

                    $actual = isDeprecated('foo');
                    expect($actual)->toBe(true);

                });

                it('deprecated on with parameters', function () {

                    $actual = isDeprecated('foo2', [1, 2]);
                    expect($actual)->toBe(true);

                });

            });

            context('not deprecated' , function () {

                it('not deprecated on without parameters', function () {

                    $actual = isDeprecated('foonotdeprecated');
                    expect($actual)->toBe(false);

                });

                it('not deprecated on with parameters', function () {

                    $actual = isDeprecated('foo2notdeprecated', [1, 2]);
                    expect($actual)->toBe(false);

                });

            });

        });

        context('function inside object' , function () {

            beforeAll(function () {
                include __DIR__ . '/Fixture/deprecatedfunctioninsideclass.php';
                include __DIR__ . '/Fixture/not-deprecatedfunctioninsideclass.php';
            });

            context('deprecated' , function () {

                it('deprecated on without parameters', function () {

                    $object = new \Aclass();
                    $actual = isDeprecated('foo', [], $object);
                    expect($actual)->toBe(true);

                });

                it('deprecated on with parameters', function () {

                    $object = new \Aclass();
                    $actual = isDeprecated('foo2', [1, 2], $object);
                    expect($actual)->toBe(true);

                });

            });

            context('not deprecated' , function () {

                it('not deprecated on without parameters', function () {

                    $object = new \AclassWithNotDeprecatedFunctions();
                    $actual = isDeprecated('foo', [], $object);
                    expect($actual)->toBe(false);

                });

                it('not deprecated on with parameters', function () {

                    $object = new \AclassWithNotDeprecatedFunctions();
                    $actual = isDeprecated('foo2', [1, 2], $object);
                    expect($actual)->toBe(false);

                });

            });

        });

        context('core php function', function () {

            it('returns not deprecated in php 7.0', function () {

                skipIf(PHP_VERSION_ID > 70000);

                $actual = isDeprecated('mcrypt_get_iv_size', [MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC]);
                expect($actual)->toBe(false);

            });

            it('returns deprecated in php 7.1', function () {

                skipIf(PHP_VERSION_ID < 70100);

                $actual = isDeprecated('mcrypt_get_iv_size', [MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC]);
                expect($actual)->toBe(true);

            });

        });

    });

});
