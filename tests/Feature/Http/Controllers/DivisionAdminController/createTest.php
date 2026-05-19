<?php

namespace Tests\Feature\Http\Controllers\DivisionAdmin;

use App\Models\User;
use App\Models\UserRole;
use App\Models\Division;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();

    $this->admin = User::factory()->create(['role_id' => UserRole::byCode('admin')->id]);
    $this->divisionAdmin = User::factory()->create(['role_id' => UserRole::byCode('division_admin')->id]);
    $this->divisionWorker = User::factory()->create(['role_id' => UserRole::byCode('division_worker')->id]);
    $this->division = Division::factory()->create();

    $this->divisionAdmin->division()->associate($this->division)->save();
    $this->divisionWorker->division()->associate($this->division)->save();
});

describe('DivisionAdminController@create', function () {

    test('Администратор может открыть форму создания', function () {
        $this->actingAs($this->admin)
            ->get(route('division-admins.create', ['division' => $this->division->id]))
            ->assertOk()
            ->assertInertia(fn($page) => $page->component('pages/division-admins/create'));
    });

    test('Администратор подразделения может открыть форму создания для своего подразделения', function () {
        $this->actingAs($this->divisionAdmin)
            ->get(route('division-admins.create', ['division' => $this->division->id]))
            ->assertOk()
            ->assertInertia(fn($page) => $page->component('pages/division-admins/create'));
    });

    test('Администратор подразделения не может открыть форму чужого подразделения', function () {
        $otherDivision = Division::factory()->create();

        $this->actingAs($this->divisionAdmin)
            ->get(route('division-admins.create', ['division' => $otherDivision->id]))
            ->assertStatus(403);
    });

    test('Работник подразделения не может открыть форму создания', function () {
        $this->actingAs($this->divisionWorker)
            ->get(route('division-admins.create', ['division' => $this->division->id]))
            ->assertStatus(403);
    });

    test('Неавторизованный пользователь не может открыть форму', function () {
        $this->get(route('division-admins.create', ['division' => $this->division->id]))
            ->assertRedirect(route('login'));
    });
});
