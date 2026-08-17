<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main__users_roles', function (Blueprint $table) {
            $table->boolean('is_subscribe_available')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('main__users_roles', function (Blueprint $table) {
            $table->dropColumn('is_subscribe_available');
        });
    }
};
