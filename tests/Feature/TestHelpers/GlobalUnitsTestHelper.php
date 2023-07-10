<?php

namespace Feature\TestHelpers;

use Faker\Generator;

class GlobalUnitsTestHelper {

    public static function generateGlobalUnitData(Generator $faker,$default = false): array {
        return [
            'name' => $faker->name,
            'converter' => $faker->randomFloat(2, 0, 100),
            'default' => $default
        ];
    }

}
