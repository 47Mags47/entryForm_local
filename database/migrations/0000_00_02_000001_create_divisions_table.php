<?php

use App\Models\City;
use App\Models\Division;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('main__divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->text('url')->nullable();

            $table->foreignId('city_id')->constrained(new City()->getTable());
            $table->foreignId('parent_id')->nullable()->constrained(new Division()->getTable());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main__divisions');
    }
};
