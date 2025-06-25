<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShelfEntry extends Model
{
    protected $fillable = [
        'user_id',
        'product_name',
        'unit_name',
        'unit_id',
        'amount',
        'bought_at',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'bought_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $with = [
        'unit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(GlobalUnit::class, 'unit_id');
    }
}
