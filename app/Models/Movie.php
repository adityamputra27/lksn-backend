<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'minute_length', 'picture_url'];

    protected $appends = ['picture'];

    public function getPictureAttribute()
    {
        return asset('pictures/'.$this->picture_url);
    }
}
