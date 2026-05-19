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

describe('SubscribeController@index', function () {

    test('Администратор видит записи подразделения', function () {
        $this->actingAs($this->admin)
            ->get(route('subscribes.index', $this->divisionA))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('pages/subscribes/index')
                ->where('division.data.id', $this->divisionA->id)
            );
    });

    test('Администратор подразделения видит только обращения своего подразделения', function () {
        $this->actingAs($this->division_admin)
            ->get(route('subscribes.index', $this->divisionA))
            ->assertOk();

        $visible = Subscribe::whereHasAccess()->pluck('division_id')->unique()->toArray();

        expect($visible)->toContain($this->divisionA->id)
                        ->not->toContain($this->divisionB->id);
    });

    test('Работник видит только свои обращения', function () {
        $this->actingAs($this->division_worker)
            ->get(route('subscribes.index', $this->divisionA))
            ->assertOk();

        $visible = Subscribe::whereHasAccess()->pluck('worker_id')->unique()->toArray();

        expect($visible)->toContain($this->division_worker->id);
    });

    test('Гость не может просматривать список обращений', function () {
        $this->get(route('subscribes.index', $this->divisionA))
            ->assertRedirect('/login');
    });
});
