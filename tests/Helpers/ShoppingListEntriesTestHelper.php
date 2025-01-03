<?php

namespace Tests\Helpers;

use Faker\Generator;

class ShoppingListEntriesTestHelper {

    public static function generateRandomRawShoppingListEntryData(Generator $faker): array {
        return [
            'product_name' => $faker->name,
            'type' => 'raw',
            'checked' => $faker->boolean(),
            'category_id' => 0
        ];
    }

    public static function generateRandomRawProductShoppingListEntryData(Generator $faker, $globalProductID = 1): array {
        return [
            'product_name' => $faker->name,
            'unit_id' => $globalProductID,
            'amount' => (float)$faker->randomDigit(),
            'type' => 'raw_product',
            'checked' => $faker->boolean(),
            'category_id' => 0
        ];
    }


}
