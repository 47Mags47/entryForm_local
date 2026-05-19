<?php

use App\Models\{User, UserRole, City};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->faker = FakerFactory::create('ru_RU');
    $this->seed();

    $this->admin = User::factory()->make(['role_id' => UserRole::byCode('admin')->id]);
    $this->division_admin = User::factory()->make(['role_id' => UserRole::byCode('division_admin')->id]);
    $this->division_worker = User::factory()->make(['role_id' => UserRole::byCode('division_worker')->id]);
    $this->attrs = ['name' => $this->faker->city()];
});

describe('CityController@destroy', function () {

    test('Администратор может удалить город', function () {
        $city = City::factory()->create($this->attrs);

        $this->actingAs($this->admin)
            ->delete(route('cities.destroy', $city))
            ->assertRedirect(route('cities.index'));

        $this->assertSoftDeleted('main__cities', ['id' => $city->id]);

    });

    test('Работник не может удалить город', function () {
        $city = City::factory()->create($this->attrs);

        $this->actingAs($this->division_worker)
            ->delete(route('cities.destroy', $city))
            ->assertStatus(403);

        $this->assertDatabaseHas('main__cities', ['id' => $city->id]);
    });

    test('Администратор подразделения не может удалить город', function () {
        $city = City::factory()->create($this->attrs);

        $this->actingAs($this->division_admin)
            ->delete(route('cities.destroy', $city))
            ->assertStatus(403);

        $this->assertDatabaseHas('main__cities', ['id' => $city->id]);
    });

    test('Неавторизованный пользователь не может удалить город', function () {
        $city = City::factory()->create($this->attrs);

        $this->delete(route('cities.destroy', $city))
            ->assertRedirect('/login');

        $this->assertDatabaseHas('main__cities', ['id' => $city->id]);
    });
});
