<?php

use App\Models\{User, UserRole, Service};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;
use Tests\TestLogger;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->faker = FakerFactory::create('ru_RU');
    $this->seed();

    $this->admin = User::factory()->create(['role_id' => UserRole::byCode('admin')->id]);
    $this->division_admin = User::factory()->create(['role_id' => UserRole::byCode('division_admin')->id]);
    $this->division_worker = User::factory()->create(['role_id' => UserRole::byCode('division_worker')->id]);

    $this->attrs = [
        'name' => $this->faker->paragraph(2),
        'duration' => $this->faker->time('H:i'),
    ];
});

describe('ServiceController@update', function () {

    test('Администратор может обновить услугу с валидными данными', function () {
        TestLogger::start('Администратор может обновить услугу с валидными данными');

        $service = Service::factory()->create($this->attrs);
        $newData = [
            'name' => $this->faker->paragraph(2),
            'duration' => $this->faker->time('H:i'),
        ];

        TestLogger::info('Исходные данные', $this->attrs);
        TestLogger::info('Новые данные', $newData);

        $response = $this->actingAs($this->admin)
            ->put(route('services.update', $service), $newData);

        TestLogger::info('HTTP статус', ['status' => $response->status()]);
        TestLogger::info('Redirect', ['to' => $response->headers->get('Location')]);

        $this->assertDatabaseHas('main__services', $newData);

        TestLogger::end();
    });

    test('Администратор не может обновить услугу с невалидными данными', function () {
        TestLogger::start('Администратор не может обновить услугу с невалидными данными');

        $service = Service::factory()->create($this->attrs);
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'duration' => ['required', 'date_format:H:i'],
        ];

        foreach ($rules as $field => $fieldRules) {
            $invalidValues = generateInvalidData($field, $fieldRules);

            foreach ($invalidValues as $invalid) {
                $badAttrs = [$field => $invalid];

                TestLogger::info("Пробуем невалидные данные для поля '{$field}'", $badAttrs);

                $response = $this->actingAs($this->admin)
                    ->put(route('services.update', $service), $badAttrs);

                TestLogger::info('HTTP статус', ['status' => $response->status()]);

                $this->assertDatabaseMissing('main__services', $badAttrs);
            }
        }

        TestLogger::end();
    });

    test('Работник не может обновить услугу', function () {
        TestLogger::start('Работник не может обновить услугу');

        $service = Service::factory()->create($this->attrs);
        $newData = [
            'name' => $this->faker->paragraph(2),
            'duration' => $this->faker->time('H:i'),
        ];

        TestLogger::info('Новые данные', $newData);

        $response = $this->actingAs($this->division_worker)
            ->put(route('services.update', $service), $newData);

        TestLogger::info('HTTP статус', ['status' => $response->status()]);
        $this->assertDatabaseMissing('main__services', $newData);

        TestLogger::end();
    });

    test('Администратор подразделения не может обновить услугу', function () {
        TestLogger::start('Администратор подразделения не может обновить услугу');

        $service = Service::factory()->create($this->attrs);
        $newData = [
            'name' => $this->faker->paragraph(2),
            'duration' => $this->faker->time('H:i'),
        ];

        TestLogger::info('Новые данные', $newData);

        $response = $this
            ->actingAs($this->division_admin)
            ->put(route('services.update', $service), $newData);

        TestLogger::info('HTTP статус', ['status' => $response->status()]);
        $this->assertDatabaseMissing('main__services', $newData);

        TestLogger::end();
    });

    test('Гость не может обновить услугу', function () {
        TestLogger::start('Гость не может обновить услугу');

        $service = Service::factory()->create($this->attrs);
        $newData = [
            'name' => $this->faker->paragraph(2),
            'duration' => $this->faker->time('H:i'),
        ];

        TestLogger::info('Новые данные', $newData);

        $response = $this->put(route('services.update', $service), $newData);

        TestLogger::info('HTTP статус', ['status' => $response->status()]);
        $this->assertDatabaseMissing('main__services', $newData);

        TestLogger::end();
    });
});
