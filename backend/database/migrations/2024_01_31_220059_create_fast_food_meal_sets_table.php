<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fast_food_meal_sets', function (Blueprint $table) {
            $table->id();
            $table->integer('fast_food_store_id');
            $table->string('name');
            $table->string('image')->nullable();
            $table->integer('calories_per_serving')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fast_food_meal_sets');
    }
};
