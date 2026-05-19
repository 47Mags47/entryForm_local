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

describe('DivisionAdminController@destroy', function () {

    test('Админ может удалить администратора подразделения', function () {
        $user = User::factory()->create(['role_id' => UserRole::byCode('division_admin')->id]);
        $user->division()->associate($this->division)->save();

        $this->actingAs($this->admin)
            ->delete(route('division-admins.destroy', ['division' => $this->division->id, 'division_admin' => $user->id]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('main__users', [
            'id' => $user->id,
            'role_id' => UserRole::byCode('division_worker')->id,
        ]);
    });

    test('Админ подразделения не может удалить самого себя', function () {
        $this->actingAs($this->division_admin)
            ->delete(route('division-admins.destroy', ['division' => $this->division->id, 'division_admin' => $this->division_admin->id]))
            ->assertRedirect()
            ->assertSessionHas('warning');

        $this->assertDatabaseHas('main__users', [
            'id' => $this->division_admin->id,
            'role_id' => UserRole::byCode('division_admin')->id,
        ]);
    });

    test('Админ подразделения может удалить администратора своего подразделения', function () {
        $user = User::factory()->create(['role_id' => UserRole::byCode('division_admin')->id]);
        $user->division()->associate($this->division)->save();

        $this->actingAs($this->division_admin)
            ->delete(route('division-admins.destroy', ['division' => $this->division->id, 'division_admin' => $user->id]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('main__users', [
            'id' => $user->id,
            'role_id' => UserRole::byCode('division_worker')->id,
        ]);
    });

    test('Админ подразделения не может удалить администратора чужого подразделения', function () {
        $otherDivision = Division::factory()->create();
        $otherAdmin = User::factory()->create(['role_id' => UserRole::byCode('division_admin')->id]);
        $otherAdmin->division()->associate($otherDivision)->save();

        $this->actingAs($this->division_admin)
            ->delete(route('division-admins.destroy', ['division' => $otherDivision->id, 'division_admin' => $otherAdmin->id]))
            ->assertStatus(403);

        $this->assertDatabaseHas('main__users', [
            'id' => $otherAdmin->id,
            'role_id' => UserRole::byCode('division_admin')->id,
        ]);
    });

    test('Работник подразделения не может удалить администратора', function () {
        $this->actingAs($this->division_worker)
            ->delete(route('division-admins.destroy', ['division' => $this->division->id, 'division_admin' => $this->division_admin->id]))
            ->assertStatus(403);

        $this->assertDatabaseHas('main__users', [
            'id' => $this->division_admin->id,
            'role_id' => UserRole::byCode('division_admin')->id,
        ]);
    });

    test('Неавторизованный пользователь не может удалить администратора подразделения', function () {
        $user = User::factory()->create(['role_id' => UserRole::byCode('division_admin')->id]);
        $user->division()->associate($this->division)->save();


        $this->delete(route('division-admins.destroy', ['division' => $this->division->id, 'division_admin' => $this->division_admin->id]))
            ->assertRedirect('/login');

        $this->assertDatabaseHas('main__users', ['id' => $user->id]);
    });
});
