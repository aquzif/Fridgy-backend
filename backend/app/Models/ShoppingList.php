<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model {
    protected $fillable = [
        'name',
        'user_id',
        'type',
        'default',
        'sort',
    ];

    protected $casts = [
        'default' => 'boolean',
        'sort' => 'boolean',
    ];

    protected $with = [
        'entries',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            if($model->default) {
                ShoppingList::where('user_id', $model->user_id)
                    ->update(['default' => false]);
            }
        });

        static::updating(function ($model) {
            if($model->default) {
                ShoppingList::where('user_id', $model->user_id)
                    ->where('id', '!=', $model->id)
                    ->update(['default' => false]);

            }
        });
    }


    function entries(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(ShoppingListEntry::class);
    }



}
