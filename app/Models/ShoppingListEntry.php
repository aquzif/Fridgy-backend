<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingListEntry extends Model {
    protected $fillable = [
        'shopping_list_id',
        'grocery_product_id',
        'product_name',
        'unit_name',
        'amount',
        'grams_per_amount',
    ];
}
