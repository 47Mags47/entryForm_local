<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main__division_shedule', function (Blueprint $table) {
            $table->time('lunch_start')->nullable()->change();
            $table->time('lunch_end')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('main__division_shedule', function (Blueprint $table) {
            $table->time('lunch_start')->nullable(false)->change();
            $table->time('lunch_end')->nullable(false)->change();
        });
    }
};
