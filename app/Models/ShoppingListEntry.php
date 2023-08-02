<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingListEntry extends Model {
    protected $fillable = [
        'shopping_list_id',
        'product_name',
        'unit_name',
        'amount',
        'checked',
        'unit_id',
        'type',
        'category_id',
    ];

    protected $casts = [
        'checked' => 'boolean',
    ];

    protected $with = [
        'unit',
        'productCategory'
    ];

    function getShoppingList(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(ShoppingList::class,'shopping_list_id','id');
    }

    function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(GlobalUnit::class);
    }

    function productCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(ProductCategory::class,'category_id');
    }

}
