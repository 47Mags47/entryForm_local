<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserRoleSeeder::class);
        $this->call(RootSeeder::class);
        $this->call(DayOfTheWeekSeeder::class);

        if(in_array(config('app.env'), ['local', 'testing'])){
            $this->call(Example\ExampleSeeder::class);
        }

    }
}
