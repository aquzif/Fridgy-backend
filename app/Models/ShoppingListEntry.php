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
    ];

    protected $casts = [
        'checked' => 'boolean',
    ];


    function getShoppingList(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(ShoppingList::class);
    }

    function getUnit(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(GlobalUnit::class);
    }

}
