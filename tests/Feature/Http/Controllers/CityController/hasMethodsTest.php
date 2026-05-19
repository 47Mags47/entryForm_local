<?php

use App\Http\Controllers\CityController;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('CityController', function () {
    test('Содержит только нужные методы', function () {
        $expected = ['index', 'create', 'store', 'edit', 'update', 'destroy'];
        $methods = get_class_methods(CityController::class);

        expect(array_diff($methods, $expected))
            ->toBeEmpty("Контроллер содержит лишние методы");
    });
});
