<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function series()
    {
        return $this->belongsTo(Series::class, 'series_id');
    }
    use HasFactory;

    
}
