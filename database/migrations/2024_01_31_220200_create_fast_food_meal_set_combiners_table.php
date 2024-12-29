<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fast_food_meal_in_sets', function (Blueprint $table) {
            $table->id();
            $table->integer('meal_id');
            $table->integer('set_id');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fast_food_meal_in_sets');
    }
};
