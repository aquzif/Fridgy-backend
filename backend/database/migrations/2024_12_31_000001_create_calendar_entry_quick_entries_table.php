<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calendar_entry_quick_entries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('calories');
            $table->integer('calendar_entry_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_entry_quick_entries');
    }
};
