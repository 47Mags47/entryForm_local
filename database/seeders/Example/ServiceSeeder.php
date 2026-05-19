<?php

namespace Database\Seeders\Example;

use App\Models\Service;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserService;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = Service::factory(5)->create();

        User::where('role_id', UserRole::byCode('division_worker')->id)->each(function ( User $user) use ($services) {
            UserService::create([
                'user_id' => $user->id,
                'service_id' => $services->random()->id,
            ]);
        });
    }
}
