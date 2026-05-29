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
        User::create([
            'first_name' => 'root',
            'last_name' => '',
            'middle_name' => '',
            'email' => 'root',
            'password' => Hash::make('root'),
            'division_id' => Division::count() > 0 ? 1: null,
            'role_id' => UserRole::byCode('admin')->id,
            'email_verified_at' => now(),
        ]);

        User::create([
            'first_name' => 'admin',
            'last_name' => '',
            'middle_name' => '',
            'email' => 'admin',
            'password' => Hash::make('admin'),
            'division_id' => Division::count() > 0 ? 1: null,
            'role_id' => UserRole::byCode('division_admin')->id,
            'email_verified_at' => now(),
        ]);
    }
}
