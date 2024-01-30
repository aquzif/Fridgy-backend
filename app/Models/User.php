<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
        'meals_per_day',
        'weight',
        'height',
        'calories_passive',
        'physical_activity_coefficient',
        'calories_per_day',
        'bmi',
        'age',
        'gender',
        'kg_to_lose_per_week'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function calendarEntries(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(CalendarEntry::class);
    }

    //recalculate on update
    protected static function booted() {
        static::updated(function (User $user) {
            $user->recalculate();
        });
    }


    public function recalculate() {



        $height = $this->height;
        $weight = $this->weight;
        $age = $this->age;
        $gender = $this->gender;
        $physical_activity_coefficient = $this->physical_activity_coefficient;
        $kg_to_lose_per_week = $this->kg_to_lose_per_week;

        //make sure all fields are filled
        if($height === 0 || $weight === 0 || $age === 0 || $gender === '' || $physical_activity_coefficient === 0 || $kg_to_lose_per_week === 0) {
            return;
        }


        if($gender === 'female') {
            $bmr = 655.1 + (9.563 * $weight) + (1.85 * $height) - (4.676 * $age);
        } else {
            $bmr = 66.5 + (13.75 * $weight) + (5.003 * $height) - (6.775 * $age);
        }
        $bmr = round($bmr,2);


        $this->calories_passive = $bmr;

        $this->bmi = $weight / (($height / 100) * ($height / 100));

        $this->calories_per_day = $this->calories_passive * $physical_activity_coefficient - ($kg_to_lose_per_week * 7000 / 7);

        $this->calories_per_day = round($this->calories_per_day,2);
        $this->bmi = round($this->bmi,2);


        $this->saveQuietly();
    }
}
