<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ritual extends Model
{
    protected $fillable = [
        'start',
        'end',
        'duration',
        'window',
    ];
   
}
