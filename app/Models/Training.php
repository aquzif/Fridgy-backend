<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'date',
        'name',
        'calories',
        'duration',
        'user_id',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
}
