<?php

use App\Models\Division;
use App\Models\DivisionGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('main__divisions', function (Blueprint $table) {
            $table->dropForeign('main__divisions_parent_id_foreign');
            $table->dropColumn('parent_id');
            $table->foreignId('group_id')->nullable()->constrained(new DivisionGroup()->getTable());

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main__divisions', function (Blueprint $table) {
            $table->dropForeign('main__divisions_group_id_foreign');
            $table->dropColumn('group_id');
            $table->foreignId('parent_id')->nullable()->constrained(new Division()->getTable());
        });
    }
};
