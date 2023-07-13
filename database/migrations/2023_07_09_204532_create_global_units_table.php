<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void {
            Schema::create('global_units', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->decimal('converter');
                $table->boolean('default');
                $table->timestamps();
            });
        }

        public function down(): void {
            Schema::dropIfExists('global_units');
        }
    };
