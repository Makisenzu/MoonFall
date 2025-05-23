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
    public function user(){
        return $this->belongsTo(User::class, 'users_id');
    }
}
