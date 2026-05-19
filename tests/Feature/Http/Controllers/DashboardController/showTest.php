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

describe('DashboardController@show', function () {
    test('Пользователь может просматривать только свою страницу дашборда', function () {
        $this->actingAs($this->user)
            ->get(route('user.show', $this->user))
            ->assertOk()
            ->assertInertia(
                fn($page) => $page
                    ->component('pages/dashboard/show')
            );
    });

    test('Пользователь не может просматривать дашборд другого пользователя', function () {
        $this->actingAs($this->user)
            ->get(route('user.show', $this->anotherUser))
            ->assertForbidden();
    });

    test('Гость не может просматривать дашборд', function () {
        $this->get(route('user.show', $this->user))
            ->assertRedirect('/login');
    });
});
