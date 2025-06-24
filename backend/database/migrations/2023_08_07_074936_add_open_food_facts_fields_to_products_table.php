<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->string('open_food_facts_id')->nullable();
            $table->string('barcode')->nullable();
            $table->string('barcode_type')->nullable();
        });
    }

    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('open_food_facts_id');
            $table->dropColumn('barcode');
            $table->dropColumn('barcode_type');
        });
    }
};
