<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calendar_entry_ingredients', function (Blueprint $table) {
            $table->id();
            $table->integer('ingredient_id');
            $table->integer('unit_id');
            $table->decimal('amount');
            $table->integer('calendar_entry_id');
            $table->decimal('calories')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_entry_ingredients');
    }
};
