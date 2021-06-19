<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wake extends Model
{
    protected $fillable = [
        'number', 
        'timeWokeUp', 
        'timeSlept',
        'duration',
        'sleepingMode', 
    ];
}
