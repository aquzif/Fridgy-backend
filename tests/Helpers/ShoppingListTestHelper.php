<?php

namespace Tests\Helpers;
use Faker\Generator;

class ShoppingListTestHelper {

    public static function generateRandomShoppingListData(Generator $faker,$type = 'default'): array{
        return [
            'name' => $faker->name,
            'type' => $type,
        ];
    }

}
