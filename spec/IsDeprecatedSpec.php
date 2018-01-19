<?php

namespace IsDeprecatedSpec;

use function IsDeprecated\isDeprecatedUser;
use function IsDeprecated\isDeprecatedCore;
use Exception;

describe('IsDeprecated', function () {

    describe('isDeprecatedUser()', function () {

        context('independent function' , function () {

            beforeAll(function () {
                include __DIR__ . '/Fixture/deprecatedfunction.php';
                include __DIR__ . '/Fixture/not-deprecatedfunction.php';
            });

            context('deprecated' , function () {

                it('deprecated', function () {

                    $actual = isDeprecatedUser('foo');
                    expect($actual)->toBe(true);

                });

            });

            context('not deprecated' , function () {

                it('not deprecated', function () {

                    $actual = isDeprecatedUser('foonotdeprecated');
                    expect($actual)->toBe(false);

                });

            });

        });

        context('different trigger_error' , function () {

            beforeAll(function () {
                include __DIR__ . '/Fixture/another-trigger-error.php';
            });

            context('deprecated' , function () {

                it('show actual error', function () {

                    $closure = function () {
                        isDeprecatedUser('another_trigger_error');
                    };
                    expect($closure)->toThrow(new Exception(
                        'function another_trigger_error has trigger_error but not E_USER_DEPRECATED'
                    ));

                });

            });

        });

        context('missplaced trigger_error' , function () {

            context('deprecated' , function () {

                it('show actual error', function () {

                    $closure = function () {
                        isDeprecatedUser('missPlacedDeprecated');
                    };
                    expect($closure)->toThrow(new Exception(
                        'function missPlacedDeprecated has trigger_error and E_USER_DEPRECATED token but misplaced'
                    ));

                });

            });

        });

        context('function inside class' , function () {

            beforeAll(function () {
                include __DIR__ . '/Fixture/deprecatedfunctioninsideclass.php';
                include __DIR__ . '/Fixture/not-deprecatedfunctioninsideclass.php';
                include __DIR__ . '/Fixture/call-isDeprecated-user-inside-class.php';
            });

            context('deprecated' , function () {

                it('deprecated with pass string class name at first index of array parameter', function () {

                    $actual = isDeprecatedUser(['Aclass', 'foo']);
                    expect($actual)->toBe(true);

                });

                it('deprecated with pass object of class at first index of array parameter', function () {

                    $actual = isDeprecatedUser([new \Aclass(), 'foo']);
                    expect($actual)->toBe(true);

                });

                it('deprecated with pass string class name::function name', function () {

                    $actual = isDeprecatedUser('Aclass::foo');
                    expect($actual)->toBe(true);

                });

            });

            context('not deprecated' , function () {

                it('not deprecated with pass string class name at first index of array parameter', function () {

                    $actual = isDeprecatedUser(['AclassWithNotDeprecatedFunctions', 'foo']);
                    expect($actual)->toBe(false);

                });

                it('not deprecated with pass object of class at first index of array parameter', function () {

                    $actual = isDeprecatedUser([new \AclassWithNotDeprecatedFunctions(), 'foo']);
                    expect($actual)->toBe(false);

                });

            });

            context('deprecated check call inside the class itself as the object represent as $this' , function () {

                it('deprecated with pass object of class at first index of array parameter', function () {

                    $actual = function () {
                        $object = new \ClassWithIsDeprecatedCall();
                        $object->bar();
                    };
                    expect($actual)->toMatchEcho('#bar#');

                });

                it('deprecated with pass object of class at first index of array parameter', function () {

                    $actual = function () {
                        $object = new \ClassWithIsDeprecatedCall();
                        $object->bar2();
                    };
                    expect($actual)->toMatchEcho('#bar#');

                });

            });

        });
    });

    describe('isDeprecatedCore()', function () {

        context('core php function', function () {

            it('returns not deprecated in php 7.0', function () {

                skipIf(PHP_VERSION_ID > 70000);

                $actual = isDeprecatedCore(function () {
                    mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
                });
                expect($actual)->toBe(false);

            });

            it('returns deprecated in php 7.1', function () {

                skipIf(PHP_VERSION_ID < 70100);
                skipIf(PHP_VERSION_ID >= 70200);

                $actual = isDeprecatedCore(function () {
                    mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
                });
                expect($actual)->toBe(true);

            });

            it('returns not deprecated in php 7.1', function () {

                skipIf(PHP_VERSION_ID >= 70200);

                $actual = isDeprecatedCore(function () {
                    create_function('$a,$b', 'return "ln($a) + ln($b) = " . log($a * $b);');
                });
                expect($actual)->toBe(false);

            });

            it('returns deprecated in php 7.2', function () {

                skipIf(PHP_VERSION_ID < 70200);

                $actual = isDeprecatedCore(function () {
                    create_function('$a,$b', 'return "ln($a) + ln($b) = " . log($a * $b);');
                });
                expect($actual)->toBe(true);

            });

        });

    });

});
