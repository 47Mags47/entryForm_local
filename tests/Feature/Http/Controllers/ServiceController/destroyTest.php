<?php

use App\Models\{User, UserRole, Service};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->faker = FakerFactory::create('ru_RU');
    $this->seed();

    $this->admin = User::factory()->make(['role_id' => UserRole::byCode('admin')->id]);
    $this->division_admin = User::factory()->make(['role_id' => UserRole::byCode('division_admin')->id]);
    $this->division_worker = User::factory()->make(['role_id' => UserRole::byCode('division_worker')->id]);

    $this->attrs = [
        'name' => $this->faker->word(),
        'duration' => $this->faker->time('H:i'),
    ];
});

describe('ServiceController@destroy', function () {

    test('Администратор может удалить услугу', function () {
        $service = Service::factory()->create($this->attrs);

        $this->actingAs($this->admin)
            ->delete(route('services.destroy', $service))
            ->assertRedirect(route('services.index'));

        $this->assertSoftDeleted('main__services', ['id' => $service->id]);
    });

    test('Работник не может удалить услугу', function () {
        $service = Service::factory()->create($this->attrs);

        $this->actingAs($this->division_worker)
            ->delete(route('services.destroy', $service))
            ->assertStatus(403);

        $this->assertDatabaseHas('main__services', ['id' => $service->id]);
    });

    test('Администратор подразделения не может удалить услугу', function () {
        $service = Service::factory()->create($this->attrs);

        $this->actingAs($this->division_admin)
            ->delete(route('services.destroy', $service))
            ->assertStatus(403);

        $this->assertDatabaseHas('main__services', ['id' => $service->id]);
    });

    test('Неавторизованный пользователь не может удалить услугу', function () {
        $service = Service::factory()->create($this->attrs);

        $this->delete(route('services.destroy', $service))
            ->assertRedirect('/login');

        $this->assertDatabaseHas('main__services', ['id' => $service->id]);
    });
});
