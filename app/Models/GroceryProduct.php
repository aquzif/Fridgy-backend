<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroceryProduct extends Model {
    protected $fillable = [
        'name',
        'default_unit_id',
        'created_by',
    ];
}
