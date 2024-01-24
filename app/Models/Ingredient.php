<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model {


    protected $fillable = [
        'recipe_id',
        'product_id',
        'product_unit_id',
        'amount_in_unit',
        'amount_in_grams',
        'calories',
    ];

    protected $with = ['product', 'unit'];
    public function recipe() {
        return $this->belongsTo(Recipe::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function unit() {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }

    public static function boot() {
        parent::boot();
        self::created(function($model) {
            $model->amount_in_grams = $model->amount_in_unit * $model->unit()->first()->grams_per_unit;
            $model->calories = $model->amount_in_grams * ($model->product->nutrition_energy_kcal/100);
            $model->saveQuietly();
            $model->recipe->recalculate();
        });
        self::updated(function($model) {
            $model->amount_in_grams = $model->amount_in_unit * $model->unit()->first()->grams_per_unit;
            $model->calories = $model->amount_in_grams * ($model->product->nutrition_energy_kcal/100);
            $model->saveQuietly();
            $model->recipe->recalculate();
        });
    }

}
