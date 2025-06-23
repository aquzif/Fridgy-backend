<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEntryIngredient extends Model
{
    protected $fillable = [
        'ingredient_id',
        'unit_id',
        'amount',
        'calendar_entry_id',
        'calories',
    ];

    protected $with = [
        'ingredient',
        'unit',
    ];

    public function ingredient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'ingredient_id');
    }

    public function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GlobalUnit::class, 'unit_id');
    }

    public function calendarEntry(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CalendarEntry::class);
    }

    public function recalculate()
    {
        $product = $this->ingredient;
        $unit = $this->unit;
        if (!$product || !$unit) {
            return;
        }
        $grams = $this->amount * $unit->converter;
        $this->calories = $grams * ($product->nutrition_energy_kcal / 100);
        $this->saveQuietly();
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->calories = 0;
        });
        static::created(function ($model) {
            $model->recalculate();
            $model->calendarEntry->recalculate();
        });
        static::updated(function ($model) {
            $model->recalculate();
            $model->calendarEntry->recalculate();
        });
        static::deleted(function ($model) {
            $model->calendarEntry->recalculate();
        });
    }
}
