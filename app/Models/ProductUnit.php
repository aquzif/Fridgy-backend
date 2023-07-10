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
    }
