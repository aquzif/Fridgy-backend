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
        'fast_food_store_id'
    ];

    protected $with = ['recipe', 'fastFoodStore', 'calendarEntryFastFoodMeals'];

    public function recipe() {
        return $this->belongsTo(Recipe::class);
    }

    public function fastFoodStore(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FastFoodStore::class);
    }

    public function calendarEntryFastFoodMeals(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(CalendarEntryFastFoodMeal::class, 'calendar_entry_id', 'id');
    }

    public function recalculate() {
        $calories = 0;
        if ($this->entry_type == 'recipe') {
            $calories = $this->recipe->calories;
        } else {
            foreach ($this->calendarEntryFastFoodMeals as $meal) {
                $calories += $meal->calories_per_ration * $meal->quantity;
            }
        }
        $this->calories = $calories;
        $this->saveQuietly();
    }

}
