<?php

namespace App\Console\Commands;

use App\Models\Product;

class PrepareCategoryImport {

    public static function run() {

        $data = [];

        foreach (Product::where('category_id', 0)
                     ->orWhere('category_id',null)->get() as $item) {
            $data[] = [
                 $item['id'],
                $item['name']
            ];
        }

        //export to csv
        $fp = fopen(storage_path('product_category.csv'), 'w');
        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

    }

}
