<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserRole::create(['code' => 'admin',            'name' => 'Администратор системы']);
        UserRole::create(['code' => 'division_admin',   'name' => 'Администратор организации']);
        UserRole::create(['code' => 'division_worker',  'name' => 'Работник организации']);
    }
}
