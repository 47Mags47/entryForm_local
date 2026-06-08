<?php

namespace Database\Seeders\Example;

use App\Models\Division;
use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Division::all()->each(function (Division $division) {
            $users = User::factory(rand(1, 3))->create([
                'division_id' => $division->id,
            ]);

            foreach ($users as $user) {
                foreach (range(1, 5) as $day_of_the_week_id) {
                    WorkSchedule::create([
                        'date_start' => '08:00',
                        'date_end' => '20:00',
                        'lunch_start' => '12:00',
                        'lunch_end' => '13:00',
                        'user_id' => $user->id,
                        'day_of_the_week_id' => $day_of_the_week_id
                    ]);
                }
            }
        });
    }
}
