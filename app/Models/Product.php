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
            'category_id',
            'open_food_facts_id',
            'barcode',
            'barcode_type',
        ];



        protected $with = [
            'units',
            'productCategory'
        ];

        public function toSearchableArray(): array {
            return [
                'name' => $this->name
            ];
        }

        //on create
        protected static function boot() {
            parent::boot();

            static::retrieved(function ($product) {
                $product['category'] = $product->productCategory ? $product->productCategory->name : null;
            });

            static::updating(function ($product) {
                unset($product['category']);
            });

            static::updated(function ($product) {
                $ingredients = Ingredient::where('product_id', $product->id)->get();

                foreach ($ingredients as $ingredient) {
                    $ingredient->recipe->recalculate();
                }

            });

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

        function productCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
            return $this->belongsTo(ProductCategory::class, 'category_id');
        }


    }
