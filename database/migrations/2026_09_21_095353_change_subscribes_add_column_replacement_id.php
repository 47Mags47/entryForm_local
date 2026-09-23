<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('main__subscribes', function (Blueprint $table) {
            $table->foreignId('replacement_id')->nullable()->constrained(new User()->getTable());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main__subscribes', function (Blueprint $table) {
            $table->dropForeign(['replacement_id']);
            $table->dropColumn('replacement_id');
        });
    }
};
