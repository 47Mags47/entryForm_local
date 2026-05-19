<?php

namespace Tests\Feature\DivisionAdmin;

use App\Models\User;
use App\Models\UserRole;
use App\Models\Division;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->faker = FakerFactory::create('ru_RU');
    $this->seed();

    $this->admin = User::factory()->create(['role_id' => UserRole::byCode('admin')->id]);
    $this->division_admin = User::factory()->create(['role_id' => UserRole::byCode('division_admin')->id]);
    $this->division_worker = User::factory()->create(['role_id' => UserRole::byCode('division_worker')->id]);

    $this->division = Division::factory()->create();
    $this->division_admin->division()->associate($this->division)->save();
    $this->division_worker->division()->associate($this->division)->save();
});

describe('DivisionAdminController@store', function () {

    test('Админ может назначить администратора подразделения', function () {
        $user = User::factory()->create(['role_id' => UserRole::byCode('division_worker')->id]);
        $user->division()->associate($this->division)->save();

        $this->actingAs($this->admin)
            ->post(route('division-admins.store', ['division' => $this->division->id]), ['user_id' => $user->id])
            ->assertRedirect(route('division-admins.create', ['division' => $this->division->id]))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('main__users', [
            'id' => $user->id,
            'role_id' => UserRole::byCode('division_admin')->id,
        ]);
    });

    test('Админ подразделения может назначить администратора своего подразделения', function () {
        $user = User::factory()->create(['role_id' => UserRole::byCode('division_worker')->id]);
        $user->division()->associate($this->division)->save();

        $this->actingAs($this->division_admin)
            ->post(route('division-admins.store', ['division' => $this->division->id]), ['user_id' => $user->id])
            ->assertRedirect(route('division-admins.create', ['division' => $this->division->id]))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('main__users', [
            'id' => $user->id,
            'role_id' => UserRole::byCode('division_admin')->id,
        ]);
    });

    test('Админ подразделения не может назначить администратора в чужом подразделении', function () {
        $otherDivision = Division::factory()->create();
        $otherUser = User::factory()->create(['role_id' => UserRole::byCode('division_worker')->id]);
        $otherUser->division()->associate($otherDivision)->save();

        $this->actingAs($this->division_admin)
            ->post(route('division-admins.store', ['division' => $otherDivision->id]), ['user_id' => $otherUser->id])
            ->assertStatus(403);

        $this->assertDatabaseHas('main__users', [
            'id' => $otherUser->id,
            'role_id' => UserRole::byCode('division_worker')->id,
        ]);
    });

    test('Работник подразделения не может назначить администратора', function () {
        $user = User::factory()->create(['role_id' => UserRole::byCode('division_worker')->id]);
        $user->division()->associate($this->division)->save();

        $this->actingAs($this->division_worker)
            ->post(route('division-admins.store', ['division' => $this->division->id]), ['user_id' => $user->id])
            ->assertStatus(403);
    });

    test('Назначение с невалидными данными', function () {
        $rules = [
            'user_id' => ['required', 'integer', 'exists:main__users,id']
        ];

        foreach ($rules as $field => $fieldRules) {
            $invalidValues = generateInvalidData($field, $fieldRules);

            foreach ($invalidValues as $invalid) {
                $badAttrs = [$field => $invalid];

                $this->actingAs($this->admin)
                    ->post(route('division-admins.store', ['division' => $this->division->id]), $badAttrs)
                    ->assertRedirectBack();

                $this->assertDatabaseMissing('main__users', [
                    'role_id' => UserRole::byCode('division_admin')->id,
                    'id' => $invalid,
                ]);
            }
        }
    });

    test('Гость не может создать администратора подразделения', function () {
        $user = User::factory()->create(['role_id' => UserRole::byCode('division_worker')->id]);
        $user->division()->associate($this->division)->save();

        $this->post(route('division-admins.store', ['division' => $this->division->id]), ['user_id' => $user->id])
            ->assertRedirect('/login');
    });
});
