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
    ];

    protected $casts = [
        'checked' => 'boolean',
    ];


    function getShoppingList(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(ShoppingList::class);
    }

}
