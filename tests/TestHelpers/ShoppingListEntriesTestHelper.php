<?php

namespace Tests\TestHelpers;

use Faker\Generator;

class ShoppingListEntriesTestHelper {

    public static function generateRandomShoppingListEntryData(Generator $faker): array {
        return [
            'product_name' => $faker->name,
            'amount' => $faker->randomDigit(),
            'unit_name' => $faker->word
        ];
    }
}
