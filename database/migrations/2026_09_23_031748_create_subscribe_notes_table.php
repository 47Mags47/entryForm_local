<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Subscribe;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('main__subscribe_notes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('worker_id')->constrained(new User()->getTable());
            $table->foreignId('subscribe_id')->constrained(new Subscribe()->getTable());

            $table->text('note')->nullable();

            $table->timestamps();

            $table->unique(['worker_id', 'subscribe_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main__subscribe_notes');
    }
};
