<?php

use App\Models\City;
use App\Models\Division;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();

    $this->faker = FakerFactory::create('ru_RU');

    $this->admin = User::factory()->make(['role_id' => UserRole::byCode('admin')->id]);
    $this->division_admin = User::factory()->make(['role_id' => UserRole::byCode('division_admin')->id]);
    $this->division_worker = User::factory()->make(['role_id' => UserRole::byCode('division_worker')->id]);

    $this->city = City::factory()->create(['name' => 'Кемерово']);

    $this->division = Division::factory()->create([
        'name' => $this->faker->company(),
        'address' => $this->faker->address(),
        'url' => 'http://example.ru',
        'city_id' => $this->city->id,
        'parent_id' => null,
    ]);
});

describe('DivisionController@show', function () {

    test('Страница доступна для администратора', function () {
        $this->actingAs($this->admin)
            ->get(route('divisions.show', $this->division->id))
            ->assertOk();
    });

    test('Страница недоступна для работника', function () {
        $this->actingAs($this->division_worker)
            ->get(route('divisions.show',$this->division->id))
            ->assertStatus(403);
    });

    test('Страница недоступна для администратора подразделения', function () {
        $this->actingAs($this->division_admin)
            ->get(route('divisions.show',$this->division->id))
            ->assertStatus(403);
    });

    test('Страница недоступна для неавторизованного пользователя', function () {
        $this->get(route('divisions.show', $this->division->id))
            ->assertRedirect('/login');
    });
});
