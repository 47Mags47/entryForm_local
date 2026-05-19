<?php

namespace Database\Seeders;

use App\Models\DayOfTheWeek;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DayOfTheWeekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DayOfTheWeek::create(['code' => 'mon', 'number' => 1, 'name' => 'Понедельник']);
        DayOfTheWeek::create(['code' => 'tue', 'number' => 2, 'name' => 'Вторник']);
        DayOfTheWeek::create(['code' => 'wed', 'number' => 3, 'name' => 'Среда']);
        DayOfTheWeek::create(['code' => 'thu', 'number' => 4, 'name' => 'Четверг']);
        DayOfTheWeek::create(['code' => 'fri', 'number' => 5, 'name' => 'Пятница']);
        DayOfTheWeek::create(['code' => 'sat', 'number' => 6, 'name' => 'Суббота']);
        DayOfTheWeek::create(['code' => 'sun', 'number' => 7, 'name' => 'Воскресенье']);
    }
}
