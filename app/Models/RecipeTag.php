<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeTag extends Model
{
    protected $fillable = [
        'name',
        'color',
    ];
}
