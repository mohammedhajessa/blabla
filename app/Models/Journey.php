<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journey extends Model
{
    protected $guarded = [];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function pickupCity()
    {
        return $this->belongsTo(City::class, 'pickup_city_id');
    }

    public function dropoffCity()
    {
        return $this->belongsTo(City::class, 'dropoff_city_id');
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
