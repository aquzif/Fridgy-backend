<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class GlobalUnit extends Model {
        protected $fillable = [
            'name',
            'converter',
            'default',
        ];
    }
