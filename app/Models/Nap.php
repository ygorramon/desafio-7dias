<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nap extends Model
{
    protected $fillable = [
        'number',
        'timeSlept',
        'timeWokeUp',
        'window',
        'duration'
    ];

    
}
