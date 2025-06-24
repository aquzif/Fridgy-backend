<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEntryQuickEntry extends Model
{
    protected $fillable = [
        'name',
        'calories',
        'calendar_entry_id'
    ];

    public function calendarEntry()
    {
        return $this->belongsTo(CalendarEntry::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function($item){
            $item->calendarEntry->recalculate();
        });
        static::updated(function($item){
            $item->calendarEntry->recalculate();
        });
        static::deleted(function($item){
            $item->calendarEntry->recalculate();
        });
    }
}
