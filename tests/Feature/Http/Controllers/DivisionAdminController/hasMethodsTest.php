<?php

use App\Http\Controllers\DivisionAdminController;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('DivisionAdminController', function () {
    test('Содержит только нужные методы', function () {
        $expected = ['index', 'create', 'store', 'destroy'];
        $methods = get_class_methods(DivisionAdminController::class);

        expect(array_diff($methods, $expected))
            ->toBeEmpty("Контроллер содержит лишние методы");
    });
});
