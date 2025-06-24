<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calendar_entry_fast_food_meals', function (Blueprint $table) {
            $table->id();
            $table->integer('fast_food_meal_id');
            $table->integer('quantity');
            $table->integer('calories_per_ration');
            $table->integer('calendar_entry_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_entry_fast_food_meals');
    }
};
