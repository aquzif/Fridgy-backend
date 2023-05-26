<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('grocery_product_units', function (Blueprint $table) {
            $table->id();
            $table->integer('grocery_product_id');
            $table->string('name');
            $table->integer('converter');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('grocery_product_units');
    }
};
