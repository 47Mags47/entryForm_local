<?php

use App\Models\{User, UserRole, City};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->faker = FakerFactory::create('ru_RU');
    $this->seed();

    $this->admin = User::factory()->create(['role_id' => UserRole::byCode('admin')->id]);
    $this->division_admin = User::factory()->create(['role_id' => UserRole::byCode('division_admin')->id]);
    $this->division_worker = User::factory()->create(['role_id' => UserRole::byCode('division_worker')->id]);

    $this->attrs = ['name' => $this->faker->city()];
});

describe('CityController@update', function () {

    test('Администратор может обновить город с валидными данными', function () {
        $city = City::factory()->create($this->attrs);
        $newData = ['name' => $this->faker->city()];

        $this->actingAs($this->admin)
            ->put(route('cities.update', $city), $newData)
            ->assertRedirect(route('cities.index'));

        $this->assertDatabaseHas('main__cities', $newData);
    });

    test('Администратор не может обновить город с невалидными данными', function () {
        $city = City::factory()->create($this->attrs);

        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[А-Яа-яЁё\\s-]+$/u'],
        ];

        foreach ($rules as $field => $fieldRules) {
            $invalidValues = generateInvalidData($field, $fieldRules);

            foreach ($invalidValues as $invalid) {
                $badAttrs = [$field => $invalid];

                $this->actingAs($this->admin)
                    ->put(route('cities.update', $city), $badAttrs)
                    ->assertRedirectBack();

                $this->assertDatabaseMissing('main__cities', $badAttrs);
            }
        }
    });

    test('Работник не может обновить город', function () {
        $city = City::factory()->create($this->attrs);
        $newData = ['name' => $this->faker->city()];

        $this->actingAs($this->division_worker)
            ->put(route('cities.update', $city), $newData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('main__cities', $newData);
    });

    test('Администратор подразделения не может обновить город', function () {
        $city = City::factory()->create($this->attrs);
        $newData = ['name' => $this->faker->city()];

        $this->actingAs($this->division_admin)
            ->put(route('cities.update', $city), $newData)
            ->assertStatus(403);

        $this->assertDatabaseMissing('main__cities', $newData);
    });

    test('Гость не может обновить город', function () {
        $city = City::factory()->create($this->attrs);
        $newData = ['name' => $this->faker->city()];

        $this->put(route('cities.update', $city), $newData)
            ->assertRedirect('/login');

        $this->assertDatabaseMissing('main__cities', $newData);
    });
});
