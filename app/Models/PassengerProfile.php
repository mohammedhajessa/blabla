<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PassengerProfile extends Model
{
    protected $guarded = [];

    protected $table = 'passenger_profiles';

    
    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }
}
