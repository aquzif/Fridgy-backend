<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeVariant extends Model
{
    protected $fillable = [
        'recipe_id',
        'name',
        'calories_per_serving',
        'is_default',
    ];

    protected $with = ['ingredients'];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function calendarEntries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CalendarEntry::class, 'recipe_variant_id');
    }

    public function recalculate(): void
    {
        $calories = 0;
        foreach ($this->ingredients as $ingredient) {
            $calories += $ingredient->calories;
        }

        $servings = max(1, $this->recipe->serving_amount);
        $this->calories_per_serving = $calories / $servings;
        $this->saveQuietly();

        if ($this->is_default) {
            $this->recipe->calories_per_serving = $this->calories_per_serving;
            $this->recipe->saveQuietly();
        }

        foreach ($this->calendarEntries as $entry) {
            $entry->calories = $this->calories_per_serving;
            $entry->saveQuietly();
        }
    }

    public static function boot()
    {
        parent::boot();

        self::deleted(function ($model) {
            $model->ingredients()->delete();
        });
    }
}
