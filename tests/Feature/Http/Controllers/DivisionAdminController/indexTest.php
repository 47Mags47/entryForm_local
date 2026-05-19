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

describe('DivisionAdminController@index', function () {

    test('Администратор может просматривать список админов подразделения', function () {
        $this->actingAs($this->admin)
            ->get(route('division-admins.index', ['division' => $this->division->id]))
            ->assertOk()
            ->assertInertia(fn($page) => $page->component('pages/division-admins/index'));
    });

    test('Администратор подразделения может просматривать список своего подразделения', function () {
        $this->actingAs($this->divisionAdmin)
            ->get(route('division-admins.index', ['division' => $this->division->id]))
            ->assertOk()
            ->assertInertia(fn($page) => $page->component('pages/division-admins/index'));
    });

    test('Администратор подразделения не может просматривать чужое подразделение', function () {
        $otherDivision = Division::factory()->create();

        $this->actingAs($this->divisionAdmin)
            ->get(route('division-admins.index', ['division' => $otherDivision->id]))
            ->assertStatus(403);
    });

    test('Работник подразделения не может просматривать список админов', function () {
        $this->actingAs($this->divisionWorker)
            ->get(route('division-admins.index', ['division' => $this->division->id]))
            ->assertStatus(403);
    });

    test('Неавторизованный пользователь не может получить доступ', function () {
        $this->get(route('division-admins.index', ['division' => $this->division->id]))
            ->assertRedirect(route('login'));
    });
});
