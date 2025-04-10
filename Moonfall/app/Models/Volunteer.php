<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $fillable = [
        'users_id',
        'latitude',
        'longitude'
    ];
    public function users(){
        return $this->hasMany(User::class);
    }
}
