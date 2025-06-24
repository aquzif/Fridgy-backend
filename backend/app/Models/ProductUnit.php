<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class ProductUnit extends Model {
        protected $fillable = [
            'product_id',
            'name',
            'grams_per_unit',
            'default',
        ];

        protected $casts = [
            'default' => 'boolean',
            'grams_per_unit' => 'int'
        ];
    }
