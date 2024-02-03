<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class FastFoodMeal extends Model
{

    use Searchable;
    protected $fillable = [
        'fast_food_store_id',
        'name',
        'image',
        'weight_in_grams',
        'calories_per_100g',
        'calories_per_item',
        'category',
    ];

    public function toSearchableArray(): array {
        return [
            'name' => $this->name
        ];
    }

    public function deleteImage(): void {
        $oldImage = $this->image;
        if($oldImage) {
            $oldImageLink = '/public/images/' .explode('/',$oldImage)[3];
            Storage::delete( $oldImageLink);
        }
    }


    function recalculate() {
        if($this->weight_in_grams == 0 || $this->calories_per_100g == 0) {
            $this->calories_per_item = 0;
            $this->saveQuietly();
            return;
        }
        $this->calories_per_item = $this->calories_per_100g * $this->weight_in_grams / 100;
        $this->saveQuietly();
    }

    protected static function boot() {
        parent::boot();

        static::created(function($fastFoodMeal) {
            $fastFoodMeal->recalculate();
            $fastFoodMeal->saveQuietly();
            $fastFoodSets = FastFoodMealSet::where('fast_food_store_id',$fastFoodMeal->fast_food_store_id)->get();
            foreach($fastFoodSets as $fastFoodSet) {
                $fastFoodSet->recalculate();
            }
        });

        static::updated(function($fastFoodMeal) {
            $fastFoodMeal->recalculate();
            $fastFoodMeal->saveQuietly();
            $fastFoodSets = FastFoodMealSet::where('fast_food_store_id',$fastFoodMeal->fast_food_store_id)->get();
            foreach($fastFoodSets as $fastFoodSet) {
                $fastFoodSet->recalculate();
            }
        });

        static::deleting(function($fastFoodMeal) {
            $fastFoodMeal->deleteImage();
            $fastFoodSets = FastFoodMealSet::where('fast_food_store_id',$fastFoodMeal->fast_food_store_id)->get();
            FastFoodMealInSet::where('meal_id',$fastFoodMeal->id)->delete();

            foreach($fastFoodSets as $fastFoodSet) {
                $fastFoodSet->recalculate();
            }
        });
    }


}
