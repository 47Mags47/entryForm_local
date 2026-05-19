<?php

use App\Http\Controllers\DivisionController;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('DivisionController', function () {
    test('Содержит только нужные методы', function () {
        $expected = ['index', 'create', 'store', 'edit', 'update', 'destroy', 'show'];
        $methods = get_class_methods(DivisionController::class);

        expect(array_diff($methods, $expected))
            ->toBeEmpty("Контроллер содержит лишние методы");
    });
});
