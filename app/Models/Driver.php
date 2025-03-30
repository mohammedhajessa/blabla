<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
class Driver extends Authenticatable
{
    use HasFactory,HasRoles,Notifiable;
    protected $guarded = [];

    public function car()
    {
        return $this->hasOne(Car::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function driverProfile()
    {
        return $this->hasOne(DriverProfile::class);
    }

    public function journeys()
    {
        return $this->hasMany(Journey::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}
