<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Division;
use App\Models\User;
use App\Models\UserRole;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('main__users_roles', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained(new User()->getTable());
            $table->foreignId('division_id')->nullable()->constrained(new Division()->getTable());
            $table->foreignId('role_id')->constrained(new UserRole()->getTable());
        });

        Schema::table(new User()->getTable(), function (Blueprint $table) {
            $table->foreignId('role_id')
                ->nullable()
                ->change();
        });

        User::all()->each(function ($user) {
            $user->roles()->attach($user->role_id, [
                'division_id' => $user->division_id,
            ]);

            $user->update([
                'division_id' => null,
                'role_id' => null,
            ]);
        });

        Schema::table(new User()->getTable(), function (Blueprint $table) {
            $table->dropForeign('main__users_division_id_foreign');
            $table->dropForeign('main__users_role_id_foreign');

            $table->dropColumn('division_id');
            $table->dropColumn('role_id');
        });
    }

    public function down(): void
    {
        Schema::table(new User()->getTable(), function (Blueprint $table) {
            $table->foreignId('division_id')->nullable()->constrained(new Division()->getTable());
            $table->foreignId('role_id')->nullable()->constrained(new UserRole()->getTable());
        });

        User::all()->each(function ($user) {
            $userRole = $user->roles()->first();

            $user->update([
                'role_id' => $userRole?->pivot->role_id,
                'division_id' => $userRole?->pivot->division_id,
            ]);
        });

        Schema::dropIfExists('main__users_roles');
    }
};
