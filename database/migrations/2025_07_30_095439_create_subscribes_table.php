<?php

use App\Models\Division;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('main__subscribes', function (Blueprint $table) {
            $table->id();

            $table->string('first_name')->nullable();
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->text('comment')->nullable();

            $table->foreignId('division_id')->constrained(new Division()->getTable());
            $table->foreignId('service_id')->constrained(new Service()->getTable());
            $table->foreignId('worker_id')->constrained(new User()->getTable());

            $table->timestamp('start_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main__subscribes');
    }
};
