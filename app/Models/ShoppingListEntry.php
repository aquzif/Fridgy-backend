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
        'product_unit_id',
    ];

    protected $casts = [
        'checked' => 'boolean',
    ];

    protected $with = [
        'unit',
        'productCategory',
        'productUnit',
    ];

    function getShoppingList(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(ShoppingList::class,'shopping_list_id','id');
    }

    function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(GlobalUnit::class,'unit_id');
    }

    function productUnit(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(ProductUnit::class,'product_unit_id');
    }

    function productCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(ProductCategory::class,'category_id');
    }

}
