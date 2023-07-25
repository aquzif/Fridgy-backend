<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalUnit extends Model {
    protected $fillable = [
        'name',
        'converter',
        'default',
    ];

    protected $casts = [
        'default' => 'boolean',
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {

            if($model->default) {
                GlobalUnit::where('default', true)->update(['default' => false]);
            }
        });
        static::updating(function ($model) {
            if($model->default) {
                GlobalUnit::where('default', true)->update(['default' => false]);
            }
        });
    }

}
