<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void {
            Schema::table('shopping_list_entries', function (Blueprint $table) {
                $table->foreignId('unit_id')->nullable()->constrained('global_units');
                $table->enum('type',['raw', 'raw_product', 'product'])->default('raw');
            });
        }

        public function down(): void {
            Schema::table('shopping_list_entries', function (Blueprint $table) {
                $table->dropForeign(['unit_id']);
                $table->dropColumn('unit_id');
                $table->dropColumn('type');
            });
        }
    };
