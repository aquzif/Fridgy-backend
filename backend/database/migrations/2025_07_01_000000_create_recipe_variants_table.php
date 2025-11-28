<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recipe_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('calories_per_serving')->default(0);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::table('ingredients', function (Blueprint $table) {
            $table->foreignId('recipe_variant_id')->nullable()->after('recipe_id')->constrained('recipe_variants');
        });

        Schema::table('calendar_entries', function (Blueprint $table) {
            $table->foreignId('recipe_variant_id')->nullable()->after('recipe_id')->constrained('recipe_variants');
        });

        $recipes = DB::table('recipes')->get();
        foreach ($recipes as $recipe) {
            $variantId = DB::table('recipe_variants')->insertGetId([
                'recipe_id' => $recipe->id,
                'name' => 'Domyślny',
                'calories_per_serving' => $recipe->calories_per_serving,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('ingredients')
                ->where('recipe_id', $recipe->id)
                ->update(['recipe_variant_id' => $variantId]);

            $ingredientsCalories = DB::table('ingredients')
                ->where('recipe_id', $recipe->id)
                ->sum('calories');

            $servings = max(1, (int)$recipe->serving_amount);
            $caloriesPerServing = $servings > 0 ? $ingredientsCalories / $servings : 0;

            DB::table('recipe_variants')
                ->where('id', $variantId)
                ->update(['calories_per_serving' => $caloriesPerServing]);

            DB::table('calendar_entries')
                ->where('recipe_id', $recipe->id)
                ->update(['recipe_variant_id' => $variantId]);
        }
    }

    public function down(): void
    {
        Schema::table('calendar_entries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recipe_variant_id');
        });

        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recipe_variant_id');
        });

        Schema::dropIfExists('recipe_variants');
    }
};
