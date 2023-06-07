<?php

namespace Tests\TestHelpers;


use Faker\Generator;

class ShoppingListTestHelper {

    public static function generateRandomShoppingListData(Generator $faker): array{
        return [
            'name' => $faker->name,
        ];
    }

}
