<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieCategory extends Model
{
    protected $fillable = [
        'category_name',
        'description',
        'cover_image_url',
        'access_control',
        'age_restriction',
        'featured_category',
        // Add other fields as needed
    ];

    public function movies()
    {
        return $this->hasMany(Movie::class, 'category_id');
    }
    
    public function series()
    {
        return $this->hasMany(Series::class, 'category_id');
    }

    use HasFactory;
}
