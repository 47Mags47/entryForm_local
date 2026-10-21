<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Subscribe;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('main__subscribe_notes', function (Blueprint $table) {
            $table->dropForeign(['subscribe_id']);

            $table->foreign('subscribe_id')->references('id')->on(new Subscribe()->getTable())->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main__subscribe_notes', function (Blueprint $table) {
            $table->dropForeign(['subscribe_id']);
            $table->foreign('subscribe_id')->references('id')->on(new Subscribe()->getTable());
        });
    }
};
