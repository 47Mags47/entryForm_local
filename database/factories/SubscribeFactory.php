<?php

namespace Database\Factories;

use App\Models\Division;
use App\Models\Service;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\Subscribe>
 */
class SubscribeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name'        => $this->faker->firstName(),
            'last_name'         => $this->faker->lastName(),
            'middle_name'       => $this->faker->userName(),
            'phone'             => $this->faker->numerify('8 (9##) ###-####'),
            'email'             => $this->faker->email(),
            'division_id'       => Division::all()->random()->id,
            'service_id'        => Service::all()->random()->id,
            'worker_id'         => User::where('role_id', UserRole::byCode('division_worker')->id)->get()->random()->id,
            'start_at'          => now()->subDays(rand(-10, 10))->subHours(rand(-24, 24))->subMinutes(rand(1, 55)),
            'comment'           => $this->faker->paragraph(2),
        ];
    }
}
