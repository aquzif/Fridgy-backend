<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('default_unit_id')->default(0);
                $table->integer('default_unit_converter')->default(1);
                $table->string('default_unit_name')->default('g');
                $table->integer('nutrition_energy_kcal')->default(0);
                $table->integer('nutrition_energy_kj')->default(0);
                $table->float('nutrition_carbs')->default(0);
                $table->float('nutrition_fat')->default(0);
                $table->float('nutrition_sugar')->default(0);
                $table->float('nutrition_protein')->default(0);
                $table->float('nutrition_salt')->default(0);
                $table->timestamps();
            });
        }

        public function down(): void {
            Schema::dropIfExists('products');
        }
    };
