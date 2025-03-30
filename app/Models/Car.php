<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $guarded = [];

    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'no_plat' => 'required|string|max:255',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'no_seats' => 'required|integer|min:1',
            'note' => 'nullable|string',
            'fuel_type' => 'required|in:bensin,solar,diesel',
            'driver_id' => 'required|exists:drivers,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];

    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

}
