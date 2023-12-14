<?php

namespace App\Console\Commands;

use App\Models\ProductCategory;

class LoadCategoryImport {

    public static function run() {


        $data = array_map('str_getcsv', file(storage_path('updated_product_category.csv')));
        foreach ($data as $fields) {
            if($fields[2] == '') continue;

            $product = \App\Models\Product::find($fields[0]);
            $category = ProductCategory::firstOrCreate([
                'name' => $fields[2]
            ]);

            $product->category_id = $category->id;
            $product->save();
        }

    }

}
