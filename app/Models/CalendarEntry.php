<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEntry extends Model
{
    protected $fillable = [
        'recipe_id',
        'entry_type',
        'calories',
        'date',
        'user_id',
        'meal_order',

    ];

    protected $with = ['recipe'];

    public function recipe() {
        return $this->belongsTo(Recipe::class);
    }

}
