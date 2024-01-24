<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'name',
        'prepare_time',
        'calories_per_serving',
        'serving_amount',
        'video_url',
        'image',
        'steps',
    ];

    protected $with = ['ingredients'];

    public function ingredients() {
        return $this->hasMany(Ingredient::class);
    }

    public function recalculate() {
        $ingredients = $this->ingredients()->get();
        $calories = 0;
        foreach ($ingredients as $ingredient) {
            $calories += $ingredient->calories;
        }

        $this->calories_per_serving = $calories / $this->serving_amount;
        $this->saveQuietly();

    }

    public static function boot() {
        parent::boot();
        self::deleting(function($model) {
            $model->ingredients()->delete();
        });
    }
}
