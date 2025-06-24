<?php

    use App\Models\GlobalUnit;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void {
            GlobalUnit::create([
                'name' => 'Sztuka',
                'converter' => 1,
                'default' => true
            ]);
            GlobalUnit::create([
                'name' => 'Kilogram',
                'converter' => 1000,
                'default' => false
            ]);
            GlobalUnit::create([
                'name' => 'Gram',
                'converter' => 1,
                'default' => false
            ]);
            GlobalUnit::create([
                'name' => 'Litr',
                'converter' => 1000,
                'default' => false
            ]);
        }

        public function down(): void {
            Schema::table('global_units', function (Blueprint $table) {
                //
            });
        }
    };
