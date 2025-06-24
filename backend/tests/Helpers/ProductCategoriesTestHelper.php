<?php

namespace Tests\Helpers;

use Faker\Generator;

class ProductCategoriesTestHelper {

    public static function generateProductCategoryData(Generator $faker) {
        return [
            'name' => $faker->name,
        ];
    }

}
