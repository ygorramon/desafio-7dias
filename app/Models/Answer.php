<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'situation',
        'response'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

}
