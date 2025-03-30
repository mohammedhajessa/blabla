<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Passenger extends Authenticatable
{
    use HasFactory,HasRoles;
    protected $guarded = [];

    public function passengerProfile()
    {
        return $this->hasOne(PassengerProfile::class);
    }

    public function journeyRequests()
    {
        return $this->hasMany(JourneyRequest::class);
    }

    public function journeyPassengers()
    {
        return $this->hasMany(JourneyPassenger::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
