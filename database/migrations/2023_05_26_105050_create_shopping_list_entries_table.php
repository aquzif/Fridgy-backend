<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shopping_list_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shopping_list_id')->constrained('shopping_lists')->onDelete('cascade');
            $table->string('product_name');
            $table->string('unit_name')->default('');
            $table->integer('amount')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('shopping_list_entries');
    }
};
