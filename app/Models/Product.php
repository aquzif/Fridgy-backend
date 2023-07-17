<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Laravel\Scout\Searchable;

    class Product extends Model {

        use Searchable;
        protected $fillable = [
            'name',
            'default_unit_id',
            'default_unit_converter',
            'default_unit_name',
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

        public function toSearchableArray(): array {
            return [
                'name' => $this->name
            ];
        }

        //on create
        protected static function boot() {
            parent::boot();

            static::created(function ($product) {
                $unit = $product->units()->create([
                    'name' => 'g',
                    'grams_per_unit' => 1,
                    'default' => true,
                ]);

                $product->update([
                    'default_unit_id' => $unit->id,
                    'default_unit_converter' => 1,
                ]);



            });

            static::deleting(function ($product) {
                $product->unsearchable();
            });

        }

        function units(): \Illuminate\Database\Eloquent\Relations\HasMany {
            return $this->hasMany(ProductUnit::class);
        }


    }
