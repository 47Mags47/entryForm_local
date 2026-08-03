<?php

namespace Database\Factories;

use App\Models\Division;
use App\Models\Service;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscribeFactory extends Factory
{
    public function definition(): array
    {
        $workerRoleId = UserRole::byCode('division_worker')->id;

        return [
            'first_name'        => $this->faker->firstName(),
            'last_name'         => $this->faker->lastName(),
            'middle_name'       => $this->faker->userName(),
            'phone'             => $this->faker->numerify('8 (9##) ###-####'),
            'email'             => $this->faker->email(),
            'division_id'       => Division::all()->random()->id,
            'service_id'        => Service::all()->random()->id,
            'worker_id'         => User::whereHas('divisions', function ($query) use ($workerRoleId) {
                $query->where('role_id', $workerRoleId);
            })->get()->random()->id,
            'start_at'          => now()->subDays(rand(-10, 10))->subHours(rand(-24, 24))->subMinutes(rand(1, 55)),
            'comment'           => $this->faker->paragraph(2),
        ];
    }
}
