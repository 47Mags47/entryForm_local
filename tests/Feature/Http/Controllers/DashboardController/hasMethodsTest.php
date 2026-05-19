<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('DashboardController', function () {
    test('Содержит только нужные методы', function () {
        $expected = ['edit', 'update', 'show'];
        $methods = get_class_methods(DashboardController::class);

        expect(array_diff($methods, $expected))
            ->toBeEmpty("Контроллер содержит лишние методы");
    });
});
