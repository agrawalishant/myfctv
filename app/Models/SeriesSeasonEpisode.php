<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeriesSeasonEpisode extends Model
{
    public function season()
    {
        return $this->belongsTo(SeriesSeason::class, 'season_id');
    }
    
    use HasFactory;
}
