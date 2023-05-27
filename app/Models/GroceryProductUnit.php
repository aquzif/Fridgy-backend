<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroceryProductUnit extends Model {
    protected $fillable = [
        'grocery_product_id',
        'name',
        'converter',
    ];
}
