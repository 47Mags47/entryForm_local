<?php

use App\Models\UserWeekends;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('main__user_service', function (Blueprint $table) {
            $table->foreignId('weekend_id')->nullable()->constrained(new UserWeekends()->getTable())->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main__user_service', function (Blueprint $table) {
            $table->dropForeign(['weekend_id']);
            $table->dropColumn('weekend_id');
        });
    }
};
