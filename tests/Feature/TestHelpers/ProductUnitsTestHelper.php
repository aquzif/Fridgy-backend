<?php

namespace Feature\TestHelpers;

use Faker\Generator;

class ProductUnitsTestHelper {

    public static function generateProductUnitData(Generator $faker, $default = false): array {
        return [
            'name' => 'g',
            'grams_per_unit' => 1,
            'default' => $default,
        ];
    }

}
