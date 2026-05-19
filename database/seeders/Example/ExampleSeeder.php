<?php

namespace Database\Seeders\Example;

use Database\Seeders\FrameStatusSeeder;
use Illuminate\Database\Seeder;

class ExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(CitySeeder::class);
        $this->call(DivisionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(SubscribeSeeder::class);
        $this->call(FrameStatusSeeder::class);
    }
}
