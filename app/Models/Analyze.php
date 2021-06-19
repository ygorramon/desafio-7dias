<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analyze extends Model
{
    protected $fillable = [
        'day',
        'date', 
        'timeWokeUp', 
        'volcanicEffect', 
        'comments'   ];

        public function naps()
        {
            return $this->hasMany('App\Models\Nap');
        }
        public function wakes()
        {
            return $this->hasMany('App\Models\Wake');
        }

        public function rituals()
        {
            return $this->hasMany('App\Models\Ritual');
        }
}
