<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FastFoodMealSet extends Model
{
    protected $fillable = [
        'fast_food_store_id',
        'name',
        'image',
        'calories_per_serving',
    ];

    protected $with = ['meals'];

    public function meals(){
        return $this->hasMany(FastFoodMealInSet::class,'set_id');
    }

    public function recalculate() {
        $calories = 0;
        foreach($this->meals as $meal) {
            $calories += $meal->meal()->first()->calories_per_item * $meal->quantity;
        }
        $this->calories_per_serving = $calories;
        $this->saveQuietly();
    }

    public function deleteImage(): void {
        $oldImage = $this->image;
        if($oldImage) {
            $oldImageLink = '/public/images/' .explode('/',$oldImage)[3];
            Storage::delete( $oldImageLink);
        }
    }


    protected static function boot() {
        parent::boot();

        static::deleting(function($fastFoodMealSet) {
            $fastFoodMealSet->deleteImage();
            FastFoodMealInSet::where('set_id',$fastFoodMealSet->id)->delete();

        });
    }

}
