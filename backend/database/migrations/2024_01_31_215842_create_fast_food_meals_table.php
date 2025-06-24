<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fast_food_meals', function (Blueprint $table) {
            $table->id();
            $table->integer('fast_food_store_id');
            $table->string('name');
            $table->string('image')->nullable();
            $table->decimal('weight_in_grams');
            $table->decimal('calories_per_100g')->default(0);
            $table->decimal('calories_per_item')->default(0);
            $table->string('category');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fast_food_meals');
    }
};
