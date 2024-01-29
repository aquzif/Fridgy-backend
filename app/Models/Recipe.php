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
            $calories += $ingredient->calories;
        }

        $this->calories_per_serving = $calories / $this->serving_amount;
        $this->saveQuietly();

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
