<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeriesSeason extends Model
{
    public function series()
    {
        return $this->belongsTo(Series::class);
    }
    public function episode()
    {
        return $this->hasMany(SeriesSeasonEpisode::class, 'series_seasons_id');
    }
    use HasFactory;
}
