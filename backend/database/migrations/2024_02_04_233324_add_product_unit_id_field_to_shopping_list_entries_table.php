<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shopping_list_entries', function (Blueprint $table) {
            $table->integer('product_unit_id')->unsigned()->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('shopping_list_entries', function (Blueprint $table) {
            $table->dropColumn('product_unit_id');
        });
    }
};
