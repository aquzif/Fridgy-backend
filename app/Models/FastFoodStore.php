<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class FastFoodStore extends Model
{
    use Searchable;
    protected $fillable = [
        'name',
        'image',
    ];

    public function toSearchableArray(): array {
        return [
            'name' => $this->name
        ];
    }

    public function meals() {
        return $this->hasMany(FastFoodMeal::class);
    }

    public function sets() {
        return $this->hasMany(FastFoodMealSet::class);
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

        static::deleting(function($fastFoodStore) {
            $fastFoodStore->deleteImage();
            $fastFoodStore->meals()->delete();
            $fastFoodStore->sets()->delete();
        });
    }

}


