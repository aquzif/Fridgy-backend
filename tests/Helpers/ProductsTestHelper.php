<?php

namespace Tests\Helpers;

use Faker\Generator;

class ProductsTestHelper {

    public static function generateProductData(Generator $faker): array {
        return [
            'name' => $faker->name,
            'nutrition_energy_kcal' => (int)$faker->randomFloat(0, 0, 100),
            'nutrition_energy_kj' => (int)$faker->randomFloat(0, 0, 100),
            'nutrition_carbs' => $faker->randomFloat(2, 0, 100),
            'nutrition_fat' => $faker->randomFloat(2, 0, 100),
            'nutrition_sugar' => $faker->randomFloat(2, 0, 100),
            'nutrition_protein' => $faker->randomFloat(2, 0, 100),
            'nutrition_salt' => $faker->randomFloat(2, 0, 100),
            'open_food_facts_id' => $faker->randomNumber(8),
            'barcode' => $faker->randomNumber(8),
            'barcode_type' => $faker->randomElement(['EAN_8', 'EAN_13']),
        ];

    }

}
