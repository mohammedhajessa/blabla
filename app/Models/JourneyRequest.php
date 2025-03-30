<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JourneyRequest extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['passenger_id', 'journey_id', 'status'];

    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }

    public function journey()
    {
        return $this->belongsTo(Journey::class);
    }

}
