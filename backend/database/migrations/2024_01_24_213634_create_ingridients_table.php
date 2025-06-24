<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->integer('recipe_id');
            $table->integer('product_id');
            $table->integer('product_unit_id');
            $table->decimal('amount_in_unit')->default(1);
            $table->decimal('amount_in_grams')->default(1);
            $table->decimal('calories')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ingredients');
    }
};
