<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as FakerFactory;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->faker = FakerFactory::create('ru_RU');
    $this->seed();

    $this->user = User::factory()->create();
    $this->anotherUser = User::factory()->create();
});

describe('DashboardController@edit', function () {
    test('Пользователь может редактировать только свой профиль', function () {
        $this->actingAs($this->user)
            ->get(route('user.edit', $this->user))
            ->assertOk()
            ->assertInertia(
                fn($page) => $page
                    ->component('pages/dashboard/edit')
            );
    });

    test('Пользователь не может редактировать чужой профиль', function () {
        $this->actingAs($this->user)
            ->get(route('user.edit', $this->anotherUser))
            ->assertForbidden();
    });

    test('Гость не может попасть на страницу редактирования профиля', function () {
        $this->get(route('user.edit', $this->user))
            ->assertRedirect('/login');
    });
});
