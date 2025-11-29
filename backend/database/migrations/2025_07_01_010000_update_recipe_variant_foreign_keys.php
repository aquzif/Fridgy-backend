<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropForeign(['recipe_variant_id']);
        });

        Schema::table('calendar_entries', function (Blueprint $table) {
            $table->dropForeign(['recipe_variant_id']);
        });

        Schema::table('ingredients', function (Blueprint $table) {
            $table->foreign('recipe_variant_id')
                ->references('id')
                ->on('recipe_variants')
                ->cascadeOnDelete();
        });

        Schema::table('calendar_entries', function (Blueprint $table) {
            $table->foreign('recipe_variant_id')
                ->references('id')
                ->on('recipe_variants')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropForeign(['recipe_variant_id']);
        });

        Schema::table('calendar_entries', function (Blueprint $table) {
            $table->dropForeign(['recipe_variant_id']);
        });

        Schema::table('ingredients', function (Blueprint $table) {
            $table->foreign('recipe_variant_id')
                ->references('id')
                ->on('recipe_variants');
        });

        Schema::table('calendar_entries', function (Blueprint $table) {
            $table->foreign('recipe_variant_id')
                ->references('id')
                ->on('recipe_variants');
        });
    }
};
