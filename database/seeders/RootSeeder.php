<?php

namespace Database\Seeders;

use App\Models\UserRole;
use App\Models\User;
use App\Models\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RootSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $root = User::factory()->create([
            'first_name' => 'root',
            'last_name' => '',
            'middle_name' => '',
            'email' => 'root',
            'password' => Hash::make('root'),
            'email_verified_at' => now(),
        ]);

        $root->roles()->attach(UserRole::byCode('admin')->first(), [
            'division_id' => null,
        ]);
    }
}
