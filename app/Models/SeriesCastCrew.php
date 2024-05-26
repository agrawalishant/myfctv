<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeriesCastCrew extends Model
{
    public function series()
    {
        return $this->belongsTo(Series::class);
    }
    use HasFactory;
}
