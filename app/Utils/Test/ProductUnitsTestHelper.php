<?php

namespace App\Utils\Test;

use Faker\Generator;

class ProductUnitsTestHelper {

    public static function generateProductUnitData(Generator $faker, $default = false): array {
        return [
            'name' => $faker->word,
            'grams_per_unit' => $faker->randomFloat(0, 1, 1000),
            'default' => $default,
        ];
    }

}
