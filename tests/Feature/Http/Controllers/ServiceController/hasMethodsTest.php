<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('ServiceController', function () {
    test('Содержит только нужные методы', function () {
        $expected = ['index', 'create', 'store', 'edit', 'update', 'destroy'];
        $methods = get_class_methods(ServiceController::class);

        expect(array_diff($methods, $expected))
            ->toBeEmpty("Контроллер содержит лишние методы");
    });
});
