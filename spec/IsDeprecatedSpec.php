<?php

namespace IsDeprecatedSpec;

use function IsDeprecated\isDeprecated;

describe('IsDeprecated', function () {

    describe('isDeprecated()', function () {

        context('independent function' , function () {

            beforeAll(function () {
                include __DIR__ . '/Fixture/deprecatedfunction.php';
            });

            it('deprecated on without parameters', function () {

                $actual = isDeprecated('foo');
                expect($actual)->toBe(true);

            });

            it('deprecated on with parameters', function () {

                $actual = isDeprecated('foo2', [1, 2]);
                expect($actual)->toBe(true);

            });

        });

        context('function inside object' , function () {

            beforeAll(function () {
                include __DIR__ . '/Fixture/deprecatedfunctioninsideclass.php';
            });

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

    });

});
