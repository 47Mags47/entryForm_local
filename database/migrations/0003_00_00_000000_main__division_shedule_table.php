<?php

use App\Models\DayOfTheWeek;
use App\Models\Division;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('main__division_shedule', function (Blueprint $table) {
            $table->id();
            $table->time('date_start');
            $table->time('date_end');
            $table->time('lunch_start');
            $table->time('lunch_end');
            $table->foreignId('division_id')->constrained(new Division()->getTable())->onDelete('cascade');
            $table->foreignId('day_of_the_week_id')->constrained(new DayOfTheWeek()->getTable());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main__division_shedule');
    }
};
