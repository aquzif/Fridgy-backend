<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void {
            Schema::create('product_units', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->string('name');
                $table->integer('grams_per_unit');
                $table->boolean('default');
                $table->timestamps();
            });
        }

        public function down(): void {
            Schema::dropIfExists('product_units');
        }
    };