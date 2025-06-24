<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FastFoodMealInSet extends Model
{
    protected $fillable = [
        'meal_id',
        'set_id',
        'quantity'
    ];

    protected $with = ['meal'];

    public function meal() {
        return $this->belongsTo(FastFoodMeal::class,'meal_id');
    }

    public function set() {
        return $this->belongsTo(FastFoodMealSet::class,'set_id');
    }

    protected static function boot() {
        parent::boot();

        static::created(function($fastFoodMealInSet) {
            $fastFoodMealInSet->set()->first()->recalculate();
        });

        static::updated(function($fastFoodMealInSet) {
            $fastFoodMealInSet->set()->first()->recalculate();
        });

        static::deleted(function($fastFoodMealInSet) {
            $fastFoodMealInSet->set()->first()->recalculate();
        });
    }

}
