<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model {
    protected $fillable = [
        'name',
        'user_id',
        'type',
        'is_default',
    ];

    protected $with = [
        'entries',
    ];


    function entries(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(ShoppingListEntry::class);
    }



}
