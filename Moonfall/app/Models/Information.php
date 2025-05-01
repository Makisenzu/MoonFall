<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    protected $fillable = [
        'news_name',
        'description',
        'urgency',
        'audience',
    ];
}
