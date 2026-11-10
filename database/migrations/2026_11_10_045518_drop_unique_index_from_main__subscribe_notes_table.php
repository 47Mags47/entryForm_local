<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('main__subscribe_notes', function (Blueprint $table) {
            $table->dropForeign(['worker_id']);
            $table->dropUnique(['worker_id', 'subscribe_id']);
            $table->foreign('worker_id')->references('id')->on((new User())->getTable());
        });
    }

    public function down(): void
    {
        Schema::table('main__subscribe_notes', function (Blueprint $table) {
            $table->dropForeign(['worker_id']);
            $table->unique(['worker_id', 'subscribe_id']);
            $table->foreign('worker_id')->references('id')->on((new User())->getTable());
        });
    }
};
