<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    protected $fillable = [
        'motherName',
        'motherPhone',
        'motherMail',
        'babyName',
        'babyBirth',
        'babySex',
        'babyAge',
        'status',

    ];

    public function form()
    {
        return $this->hasOne('App\Models\Form');
    }

    public function analyzes()
    {
        return $this->hasMany('App\Models\Analyze');
    }

    public function naps()
    {
        return $this->hasManyThrough('App\Models\Nap', 'App\Models\Analyze',
    
        'client_id', // Foreign key on users table...
        'analyze_id', // Foreign key on posts table...
        'id', // Local key on countries table...
        'id' );

    }

    public function rituals()
    {
        return $this->hasManyThrough('App\Models\Ritual', 'App\Models\Analyze',
    
        'client_id', // Foreign key on users table...
        'analyze_id', // Foreign key on posts table...
        'id', // Local key on countries table...
        'id' );

    }

    public function wakes()
    {
        return $this->hasManyThrough('App\Models\Wake', 'App\Models\Analyze',
    
        'client_id', // Foreign key on users table...
        'analyze_id', // Foreign key on posts table...
        'id', // Local key on countries table...
        'id' );

    }
    
}
