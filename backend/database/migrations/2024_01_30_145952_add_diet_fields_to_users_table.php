<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('meals_per_day')->default(0);
            $table->decimal('weight', 8, 2)->default(0);
            $table->decimal('height', 8, 2)->default(0);
            $table->decimal('calories_passive', 8, 2)->default(0);
            $table->decimal('physical_activity_coefficient', 8, 2)->default(0);
            $table->decimal('calories_per_day')->default(0);
            $table->decimal('bmi', 8, 2)->default(0);
            $table->integer('age')->default(0);
            $table->string('gender')->default('male');
            $table->decimal('kg_to_lose_per_week', 8, 2)->default(0);


        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropcolumn('meals_per_day');
            $table->dropcolumn('weight');
            $table->dropcolumn('height');
            $table->dropcolumn('calories_passive');
            $table->dropcolumn('physical_activity_coefficient');
            $table->dropcolumn('calories_per_day');
            $table->dropcolumn('bmi');
            $table->dropcolumn('age');
            $table->dropColumn('gender');
            $table->dropColumn('kg_to_lose_per_week');
        });
    }
};
