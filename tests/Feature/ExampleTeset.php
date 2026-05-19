<?php

beforeEach(function () {
    $this->permissions = [
        'index' => [
            'guest' => false,
            'division_worker' => false,
            'division_admin' => false,
            'admin' => true,
        ]
    ];
});

describe('access', function () {
    describe('guest', function () {
        test('testing', function(){

        });
    });

    describe('division worker', function () {
        //
    });

    describe('division admin', function () {
        //
    });

    describe('admin', function () {
        test('index', function () {
            $response = $this->actingAs($this->admin)
                ->get(route('cities.index'))
                ->assertOk();
        });
        test('create', function () {
            $response = $this->actingAs($this->admin)
                ->get(route('cities.create'))
                ->assertOk();
        });
        test('edit', function () {
            $response = $this->actingAs($this->admin)
                ->get(route('cities.edit'))
                ->assertOk();
        });
    });
});


