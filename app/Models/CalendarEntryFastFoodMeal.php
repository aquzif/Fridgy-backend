<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEntryFastFoodMeal extends Model
{
    protected $fillable = [
        'fast_food_meal_id',
        'quantity',
        'calories_per_ration',
        'calendar_entry_id',
    ];

    protected $with = ['fastFoodMeal'];

    public function fastFoodMeal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FastFoodMeal::class);
    }

    public function calendarEntry(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CalendarEntry::class);
    }

    public function recalculate() {
        $this->calories_per_ration = $this->fastFoodMeal->calories_per_item;
        $this->saveQuietly();

    }

    protected static function boot(){
        parent::boot();
        static::creating(function($entry){
            $entry->calories_per_ration = 0;
        });
        static::created(function($entry){
            $entry->recalculate();
            $entry->calendarEntry->recalculate();
        });
        static::updated(function($entry){
            $entry->recalculate();
            $entry->calendarEntry->recalculate();
        });
        static::deleted(function($entry){
            $entry->recalculate();
            $entry->calendarEntry->recalculate();
        });
    }
}
