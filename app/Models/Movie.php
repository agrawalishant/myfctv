<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MovieCategory;

class Movie extends Model
{
    protected $fillable = [
        'name', 'description', 'access', 'category_id', 'content_rating',
        'release_date', 'duration', 'thumbnail', 'poster', 'trailer'
    ];

    public function category()
    {
        return $this->belongsTo(MovieCategory::class, 'category_id');
    }

    public function castAndCrew()
    {
        return $this->hasMany(CastCrew::class);
    }

    public function videoVersions()
    {
        return $this->hasMany(VideoVersion::class);
    }
    
    use HasFactory;
}
