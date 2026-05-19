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

describe('CityController@edit', function () {

    test('Страница доступна для администратора', function () {
        $city = City::factory()->create($this->attrs);

        $this->actingAs($this->admin)
            ->get(route('cities.edit', ['city' => $city->id]))
            ->assertOk();
    });

    test('Страница недоступна для работника', function () {
        $city = City::factory()->create($this->attrs);

        $this->actingAs($this->division_worker)
            ->get(route('cities.edit', ['city' => $city->id]))
            ->assertStatus(403);
    });

    test('Страница недоступна для администратора подразделения', function () {
        $city = City::factory()->create($this->attrs);
        $this->actingAs($this->division_admin)
            ->get(route('cities.edit', ['city' => $city->id]))
            ->assertStatus(403);
    });

    test('Страница недоступна для неавторизованного пользователя', function () {
        $city = City::factory()->create($this->attrs);

        $this->get(route('cities.edit', ['city' => $city->id]))
            ->assertRedirect('/login');
    });
});
