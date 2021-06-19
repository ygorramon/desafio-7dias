<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'sex',
        'description',
        'detail'
    ];

    public function answers(){
        return $this->hasMany(Answer::class);
    }
}
