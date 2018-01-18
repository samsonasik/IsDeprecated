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

                it('deprecated', function () {

                    $actual = isDeprecated(function () {
                        foo();
                    });
                    expect($actual)->toBe(true);

                });

            });

            context('not deprecated' , function () {

                it('not deprecated', function () {

                    $actual = isDeprecated(function () {
                        foonotdeprecated();
                    });
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

                it('deprecated', function () {

                    $actual = isDeprecated(function () {
                       (new \Aclass())->foo();
                    });
                    expect($actual)->toBe(true);

                });

            });

            context('not deprecated' , function () {

                it('not deprecated', function () {

                    $actual = isDeprecated(function () {
                        (new \AclassWithNotDeprecatedFunctions())->foo();
                    });
                    expect($actual)->toBe(false);

                });

            });

        });

        context('core php function', function () {

            it('returns not deprecated in php 7.0', function () {

                skipIf(PHP_VERSION_ID > 70000);

                $actual = isDeprecated(function () {
                    mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
                });
                expect($actual)->toBe(false);

            });

            it('returns deprecated in php 7.1', function () {

                skipIf(PHP_VERSION_ID < 70100);

                $actual = isDeprecated(function () {
                    mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
                });
                expect($actual)->toBe(true);

            });

        });

    });

});
