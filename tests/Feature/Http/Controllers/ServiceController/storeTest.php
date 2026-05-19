<?php

use App\Models\{User, UserRole, Service};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->faker = FakerFactory::create('ru_RU');
    $this->seed();

    $this->admin = User::factory()->create(['role_id' => UserRole::byCode('admin')->id]);
    $this->division_admin = User::factory()->create(['role_id' => UserRole::byCode('division_admin')->id]);
    $this->division_worker = User::factory()->create(['role_id' => UserRole::byCode('division_worker')->id]);

    $this->attrs = [
        'name' => $this->faker->sentence(3),
        'duration' => '00:30',
    ];
});

describe('ServiceController@store', function () {

    test('Администратор может создать услугу', function () {
        $this->actingAs($this->admin)
            ->post(route('services.store'), $this->attrs)
            ->assertRedirect(route('services.index'));

        $this->assertDatabaseHas('main__services', [
            'name' => $this->attrs['name'],
            'duration' => $this->attrs['duration'],
        ]);
    });

    test('Создание услуги с невалидными данными', function () {
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'duration' => ['required', 'date_format:H:i'],
        ];

        foreach ($rules as $field => $fieldRules) {
            $invalidValues = generateInvalidData($field, $fieldRules);

            if (empty($invalidValues))
                continue;

            foreach ($invalidValues as $invalid) {
                $badAttrs = array_merge($this->attrs, [$field => $invalid]);

                $response = $this->actingAs($this->admin)
                    ->post(route('services.store'), $badAttrs);

                $response->assertStatus(302); // redirect back with validation errors
                $this->assertDatabaseMissing('main__services', [$field => $invalid]);
            }
        }
    });

    test('Работник не может создать услугу', function () {
        $this->actingAs($this->division_worker)
            ->post(route('services.store'), $this->attrs)
            ->assertStatus(403);

        $this->assertDatabaseMissing('main__services', $this->attrs);
    });

    test('Администратор подразделения не может создать услугу', function () {
        $this->actingAs($this->division_admin)
            ->post(route('services.store'), $this->attrs)
            ->assertStatus(403);

        $this->assertDatabaseMissing('main__services', $this->attrs);
    });

    test('Гость не может создать услугу', function () {
        $this->post(route('services.store'), $this->attrs)
            ->assertRedirect('/login');

        $this->assertDatabaseMissing('main__services', $this->attrs);
    });
});
