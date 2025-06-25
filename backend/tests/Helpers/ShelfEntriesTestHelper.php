<?php
namespace Tests\Helpers;

use Faker\Generator;

class ShelfEntriesTestHelper {
    public static function generateShelfEntryData(Generator $faker, $unitId = 1): array {
        return [
            'product_name' => $faker->name,
            'amount' => (float)$faker->randomDigit(),
            'unit_id' => $unitId,
            'bought_at' => $faker->date(),
            'expires_at' => $faker->date(),
        ];
    }
}
