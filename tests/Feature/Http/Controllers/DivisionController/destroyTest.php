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
        'name' => 'Старое подразделение',
        'address' => 'Старый адрес',
        'url' => 'http://example.ru',
        'city_id' => $this->city->id,
    ]);

    $this->attrs = [
        'name' => $this->faker->company(),
        'address' => $this->faker->address(),
        'url' => 'http://example.ru',
        'city_id' => $this->city->id,
        'parent_id' => $this->parent_division->id,
    ];
});

describe('DivisionController@destroy', function () {

    test('Администратор может удалить подразделение', function () {
        $division = Division::factory()->create($this->attrs);

        $this->actingAs($this->admin)
            ->delete(route('divisions.destroy', $division));

        $this->assertSoftDeleted('main__divisions', ['id' => $division->id]);
    });

    test('Работник не может удалить подразделение', function () {
        $division = Division::factory()->create($this->attrs);

        $this->actingAs($this->division_worker)
            ->delete(route('divisions.destroy', $division))
            ->assertStatus(403);

        $this->assertDatabaseHas('main__divisions', ['id' => $division->id]);
    });

    test('Администратор подразделения не может удалить подразделение', function () {
        $division = Division::factory()->create($this->attrs);

        $this->actingAs($this->division_admin)
            ->delete(route('divisions.destroy', $division))
            ->assertStatus(403);

        $this->assertDatabaseHas('main__divisions', ['id' => $division->id]);
    });

    test('Гость не может удалить подразделение', function () {
        $division = Division::factory()->create($this->attrs);

        $this->delete(route('divisions.destroy', $division))
            ->assertRedirect('/login');

        $this->assertDatabaseHas('main__divisions', ['id' => $division->id]);
    });
});
