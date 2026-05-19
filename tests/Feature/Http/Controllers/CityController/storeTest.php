<?php

use App\Models\User;
use App\Models\UserRole;
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

describe('CityController@store', function () {

    test('Администратор может создать город', function () {
        $this->actingAs($this->admin)
            ->post(route('cities.store'), $this->attrs)
            ->assertRedirect(route('cities.index'));

        $this->assertDatabaseHas('main__cities', $this->attrs);
    });

    test('Создание города с невалидными данными', function () {
        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[А-Яа-яЁё\\s-]+$/u'],
        ];

        foreach ($rules as $field => $fieldRules) {
            $invalidValues = generateInvalidData($field, $fieldRules);

            foreach ($invalidValues as $invalid) {
                $badAttrs = [$field => $invalid];

                $this->actingAs($this->admin)
                    ->post(route('cities.store'), $badAttrs)
                    ->assertRedirectBack();

                $this->assertDatabaseMissing('main__cities', $badAttrs);
            }
        }
    });

    test('Работник не может создать город', function () {
        $this->actingAs($this->division_worker)
            ->post(route('cities.store'), $this->attrs)
            ->assertStatus(403);
    });

    test('Администратор подразделения не может создать город', function () {
        $this->actingAs($this->division_admin)
            ->post(route('cities.store'), $this->attrs)
            ->assertStatus(403);
    });

    test('Гость не может создать город', function () {
        $this->post(route('cities.store'), $this->attrs)
            ->assertRedirect('/login');
    });
});
