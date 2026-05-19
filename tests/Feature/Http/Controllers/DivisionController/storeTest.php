<?php

use App\Models\{City, Division, User, UserRole};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->faker = FakerFactory::create('ru_RU');
    $this->seed();

    $this->admin = User::factory()->create(['role_id' => UserRole::byCode('admin')->id]);
    $this->division_admin = User::factory()->create(['role_id' => UserRole::byCode('division_admin')->id]);
    $this->division_worker = User::factory()->create(['role_id' => UserRole::byCode('division_worker')->id]);

    $this->city = City::factory()->create(['name' => 'Кемерово']);

    $this->parent_division = Division::factory()->create([
        'name' => $this->faker->company(),
        'address' => $this->faker->address(),
        'url' => 'http://example.ru',
        'city_id' => $this->city->id,
        'parent_id' => null,
    ]);

    $this->attrs = [
        'name' => $this->faker->company(),
        'address' => $this->faker->address(),
        'url' => $this->faker->url(),
        'city_id' => $this->city->id,
        'parent_id' => $this->parent_division->id,
        'shedules' => [
            'mon' => ['date_start' => '08:00', 'date_end' => '17:00'],
        ],
    ];
});

describe('DivisionController@store', function () {

    test('Администратор может создать подразделение', function () {
        $this->actingAs($this->admin)
            ->post(route('divisions.store'), $this->attrs)
            ->assertRedirect(route('divisions.index'));

        $this->assertDatabaseHas('main__divisions', [
            'name' => $this->attrs['name'],
            'address' => $this->attrs['address'],
            'url' => $this->attrs['url'],
            'city_id' => $this->attrs['city_id'],
            'parent_id' => $this->attrs['parent_id'],
        ]);
    });

    test('Создание подразделения с невалидными данными', function () {
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'address' => ['required', 'string', 'min:3'],
            'url' => ['nullable', 'string', 'max:255', 'url'],
            'city_id' => ['required', 'integer', 'exists:main__cities,id'],
            'parent_id' => ['nullable', 'exists:main__divisions,id'],
        ];

        foreach ($rules as $field => $fieldRules) {
            $invalidValues = generateInvalidData($field, $fieldRules);

            if (empty($invalidValues))
                continue;

            foreach ($invalidValues as $invalid) {
                $badAttrs = array_merge($this->attrs, [$field => $invalid]);

                $this->actingAs($this->admin)
                    ->post(route('divisions.store'), $badAttrs);

                $this->assertDatabaseMissing('main__divisions', [$field => $invalid]);
            }
        }
    });

    test('Работник не может создать подразделение', function () {
        $this->actingAs($this->division_worker)
            ->post(route('divisions.store'), $this->attrs)
            ->assertStatus(403);
    });

    test('Администратор подразделения не может создать подразделение', function () {
        $this->actingAs($this->division_admin)
            ->post(route('divisions.store'), $this->attrs)
            ->assertStatus(403);
    });

    test('Гость не может создать подразделение', function () {
        $this->post(route('divisions.store'), $this->attrs)
            ->assertRedirect('/login');
    });
});
