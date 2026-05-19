<?php

use App\Models\{User, UserRole, Division};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->faker = FakerFactory::create('ru_RU');
    $this->seed();

    $this->admin = User::factory()->make(['role_id' => UserRole::byCode('admin')->id]);
    $this->division_admin = User::factory()->make(['role_id' => UserRole::byCode('division_admin')->id]);
    $this->division_worker = User::factory()->make(['role_id' => UserRole::byCode('division_worker')->id]);
    $this->attrs = ['name' => $this->faker->company()];
});

describe('DivisionController@edit', function () {

    test('Страница доступна для администратора', function () {
        $division = Division::factory()->create($this->attrs);

        $this->actingAs($this->admin)
            ->get(route('divisions.edit', ['division' => $division->id]))
            ->assertOk();
    });

    test('Страница недоступна для работника', function () {
        $division = Division::factory()->create($this->attrs);

        $this->actingAs($this->division_worker)
            ->get(route('divisions.edit', ['division' => $division->id]))
            ->assertStatus(403);
    });

    test('Страница недоступна для администратора подразделения', function () {
        $division = Division::factory()->create($this->attrs);

        $this->actingAs($this->division_admin)
            ->get(route('divisions.edit', ['division' => $division->id]))
            ->assertStatus(403);
    });

    test('Страница недоступна для неавторизованного пользователя', function () {
        $division = Division::factory()->create($this->attrs);

        $this->get(route('divisions.edit', ['division' => $division->id]))
            ->assertRedirect('/login');
    });
});
