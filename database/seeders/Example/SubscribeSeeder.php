<?php

namespace Database\Seeders\Example;

use App\Models\Division;
use App\Models\Subscribe;
use Illuminate\Database\Seeder;

class SubscribeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Division::all()->each(
            fn($division) => $division->workers->each(
                fn($worker) => Subscribe::factory(5)->create(['worker_id' => $worker->id])
            )
        );
    }
}
