<?php

use App\Models\{User, UserRole, Division, Subscribe};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->faker = FakerFactory::create('ru_RU');
    $this->seed();

    $this->admin = User::factory()->create(['role_id' => UserRole::byCode('admin')->id]);
    $this->division_admin = User::factory()->create(['role_id' => UserRole::byCode('division_admin')->id]);
    $this->division_worker = User::factory()->create(['role_id' => UserRole::byCode('division_worker')->id]);

    $this->divisionA = Division::factory()->create();
    $this->divisionB = Division::factory()->create();

    $this->division_admin->division()->associate($this->divisionA)->save();
    $this->division_worker->division()->associate($this->divisionA)->save();

    $this->subscribeA = Subscribe::factory()->create([
        'division_id' => $this->divisionA->id,
        'worker_id' => $this->division_worker->id,
    ]);
    $this->subscribeB = Subscribe::factory()->create([
        'division_id' => $this->divisionB->id,
    ]);
});

describe('SubscribeController@show', function () {

    test('Администратор может видеть обращение только выбранного подразделения', function () {
        $this->actingAs($this->admin)
            ->get(route('subscribes.show', [$this->divisionA, $this->subscribeA]))
            ->assertOk();

        $this->get(route('subscribes.show', [$this->divisionB, $this->subscribeA]))
            ->assertForbidden();
    });

    test('Администратор подразделения может видеть только обращения своего подразделения', function () {
        $this->actingAs($this->division_admin)
            ->get(route('subscribes.show', [$this->divisionA, $this->subscribeA]))
            ->assertOk();

        $this->get(route('subscribes.show', [$this->divisionB, $this->subscribeB]))
            ->assertForbidden();
    });

    test('Работник может видеть только свои обращения', function () {
        $this->actingAs($this->division_worker)
            ->get(route('subscribes.show', [$this->divisionA, $this->subscribeA]))
            ->assertOk();

        $this->get(route('subscribes.show', [$this->divisionA, $this->subscribeB]))
            ->assertForbidden();
    });

    test('Гость не может просматривать обращение', function () {
        $this->get(route('subscribes.show', [$this->divisionA, $this->subscribeA]))
            ->assertRedirect('/login');
    });
});
