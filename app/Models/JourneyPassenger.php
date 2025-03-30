<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JourneyPassenger extends Model
{
    protected $fillable = ['journey_id', 'passenger_id'];

    public function journey()
    {
        return $this->belongsTo(Journey::class);
    }

    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }
}
