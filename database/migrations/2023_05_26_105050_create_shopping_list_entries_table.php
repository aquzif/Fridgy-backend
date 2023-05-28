<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shopping_list_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('shopping_list_id');
            $table->integer('grocery_product_id');
            $table->string('product_name');
            $table->string('unit_name');
            $table->integer('amount');
            $table->integer('converter');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('shopping_list_entries');
    }
};
