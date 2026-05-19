<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();

    $this->user = User::factory()->create();
    $this->anotherUser = User::factory()->create();

    $this->validData = [
        'first_name' => 'Иван',
        'middle_name' => 'Иванович',
        'last_name' => 'Иванов',
    ];

    $this->rules = [
        'first_name' => ['required', 'string', 'min:3', 'max:255'],
        'middle_name' => ['nullable', 'string', 'min:3', 'max:255'],
        'last_name' => ['nullable', 'string', 'min:3', 'max:255'],
    ];
});

describe('DashboardController@update', function () {

    test('Пользователь может обновить только свой профиль', function () {
        $this->actingAs($this->user)
            ->put(route('user.update', $this->user), $this->validData)
            ->assertRedirect(route('user.show', ['user' => $this->user->id]))
            ->assertSessionHas('success', 'ФИО успешно изменено');

        expect($this->user->fresh()->first_name)->toBe('Иван');
    });

    test('Пользователь не может обновить чужой профиль', function () {
        $this->actingAs($this->user)
            ->put(route('user.update', $this->anotherUser), $this->validData)
            ->assertForbidden();
    });

    test('Гость не может обновить профиль', function () {
        $this->put(route('user.update', $this->user), $this->validData)
            ->assertRedirect('/login');
    });

    test('Валидация не проходит при неверных данных', function () {
        foreach ($this->rules as $field => $rules) {
            foreach (generateInvalidData($field, $rules) as $invalidValue) {
                $invalidData = array_merge($this->validData, [$field => $invalidValue]);

                $response = $this->actingAs($this->user)
                    ->from(route('user.edit', $this->user))
                    ->put(route('user.update', $this->user), $invalidData);

                $response->assertSessionHasErrors($field);
            }
        }
    });
});
