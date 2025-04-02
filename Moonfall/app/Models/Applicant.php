<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $fillable = [
        'applicant_id',
        'status'
    ];
    public function user(){
        return $this->belongsTo(User::class, 'applicant_id', 'id');
    }
}
