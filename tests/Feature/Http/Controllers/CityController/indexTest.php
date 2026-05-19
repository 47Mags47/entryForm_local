<?php

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();

    $this->admin = User::factory()->make(['role_id' => UserRole::byCode('admin')->id]);
    $this->division_admin = User::factory()->make(['role_id' => UserRole::byCode('division_admin')->id]);
    $this->division_worker = User::factory()->make(['role_id' => UserRole::byCode('division_worker')->id]);
});

describe('CityController@index', function () {

    test('Страница доступна для администратора', function () {
        $this->actingAs($this->admin)
            ->get(route('cities.index'))
            ->assertOk();
    });

    test('Страница недоступна для работника', function () {
        $this->actingAs($this->division_worker)
            ->get(route('cities.index'))
            ->assertStatus(403);
    });

    test('Страница недоступна для администратора подразделения', function () {
        $this->actingAs($this->division_admin)
            ->get(route('cities.index'))
            ->assertStatus(403);
    });

    test('Страница недоступна для неавторизованного пользователя', function () {
        $this->get(route('cities.index'))
            ->assertRedirect('/login');
    });
});
