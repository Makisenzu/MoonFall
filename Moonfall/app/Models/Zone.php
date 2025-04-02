<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = [
        'location_name',
        'occupation',
        'radius',
        'latitude',
        'longitude'
    ];
}
