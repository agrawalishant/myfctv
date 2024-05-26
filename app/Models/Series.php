<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    public function cast()
    {
        return $this->hasMany(SeriesCastCrew::class);
    }

    public function seasons()
    {
        return $this->hasMany(SeriesSeason::class);
    }
    
    public function category()
    {
        return $this->belongsTo(MovieCategory::class, 'category_id');
    }
    use HasFactory;
}
