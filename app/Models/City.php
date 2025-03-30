<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name', 'region_id'];

    public function region()
    {
        return $this->belongsTo(City::class, 'region_id');
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }
}

