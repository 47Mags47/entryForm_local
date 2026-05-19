<?php

use App\Http\Controllers\SubscribeController;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('SubscribeController', function () {
    test('Содержит только нужные методы', function () {
        $expected = ['index', 'show'];
        $methods = get_class_methods(SubscribeController::class);

        expect(array_diff($methods, $expected))
            ->toBeEmpty("Контроллер содержит лишние методы");
    });
});
