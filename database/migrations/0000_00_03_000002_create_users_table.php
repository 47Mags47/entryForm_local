<?php

use App\Models\Division;
use App\Models\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('glossary__user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('main__users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('office')->nullable();

            $table->foreignId('division_id')->nullable()->constrained(new Division()->getTable());
            $table->foreignId('role_id')->constrained(new UserRole()->getTable());

            $table->rememberToken();

            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glossary__user_roles');
        Schema::dropIfExists('main__users');
    }
};
