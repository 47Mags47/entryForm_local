<?php

namespace Database\Seeders\Example;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Division::all()->each(function (Division $division) {
            User::factory(rand(1, 3))->create([
                'division_id' => $division->id,
            ]);
        });

        User::factory()->create([
            'email' => 'booba@boba.boo',
            'password' => \Hash::make('booba'),
        ]);
    }
}
