<?php

use App\Models\DayOfTheWeek;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('main__work_schedules', function (Blueprint $table) {
            $table->id();
            $table->time('date_start');
            $table->time('date_end');
            $table->time('lunch_start');
            $table->time('lunch_end');
            $table->foreignId('user_id')->constrained(new User()->getTable());
            $table->foreignId('day_of_the_week_id')->constrained(new DayOfTheWeek()->getTable());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main__work_schedules');
    }
};
