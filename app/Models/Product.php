<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Product extends Model {
        protected $fillable = [
            'name',
            'default_unit_id',
            'default_unit_converter',
            'nutrition_energy_kcal',
            'nutrition_energy_kj',
            'nutrition_carbs',
            'nutrition_fat',
            'nutrition_sugar',
            'nutrition_protein',
            'nutrition_salt',
        ];

        protected $with = [
            'units',
        ];

        //on create
        protected static function boot() {
            parent::boot();

            static::created(function ($product) {
                $product = $product->units()->create([
                    'name' => 'g',
                    'grams_per_unit' => 1,
                    'default' => true,
                ]);
            });
        }

        function units(): \Illuminate\Database\Eloquent\Relations\HasMany {
            return $this->hasMany(ProductUnit::class);
        }


    }
