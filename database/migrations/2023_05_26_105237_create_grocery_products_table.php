<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('grocery_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('default_unit_id');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('grocery_products');
    }
};
