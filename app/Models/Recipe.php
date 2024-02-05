<?php

namespace App\Models;

use App\Utils\URLUtils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Recipe extends Model {

    use Searchable;
    protected $fillable = [
        'name',
        'prepare_time',
        'calories_per_serving',
        'serving_amount',
        'video_url',
        'image',
        'steps',
        'tags'
    ];

    protected $with = ['ingredients'];

    public function toSearchableArray(): array {
        return [
            'name' => $this->name
        ];
    }

    public function ingredients() {
        return $this->hasMany(Ingredient::class);
    }

    public function recalculate() {
        $ingredients = $this->ingredients()->get();
        $calories = 0;
        foreach ($ingredients as $ingredient) {
            $ingredient->amount_in_grams = $ingredient->amount_in_unit * $ingredient->unit()->first()->grams_per_unit;
            $ingredient->calories = $ingredient->amount_in_grams * ($ingredient->product->nutrition_energy_kcal/100);
            $ingredient->saveQuietly();
            $calories += $ingredient->calories;
        }

        $this->calories_per_serving = $calories / $this->serving_amount;
        $this->saveQuietly();

        $entries = $this->calendarEntries()->get();
        foreach ($entries as $entry) {
            $entry->calories = $this->calories_per_serving;
            $entry->saveQuietly();
        }

    }

    public function calendarEntries() {
        return $this->hasMany(CalendarEntry::class);
    }

    public function deleteImage(): void {
        $oldImage = $this->image;
        if($oldImage) {
            $oldImageLink = '/public/images/' .explode('/',$oldImage)[3];
            Storage::delete( $oldImageLink);
        }
    }

    public static function boot() {
        parent::boot();
        self::creating(function($model) {
            $model->tags = '[]';
        });
        self::deleting(function($model) {

            if($model->calendarEntries()->count() > 0)
                throw new \Exception('Cannot delete recipe with calendar entries');



            $model->ingredients()->delete();
            $model->deleteImage();
        });
        self::updating(function($model) {
            if($model['video_url'])
                if(!URLUtils::isValidYoutubeUrl($model['video_url']))
                    throw new \Exception('Invalid youtube url');
            $model->recalculate();

        });
    }
}
