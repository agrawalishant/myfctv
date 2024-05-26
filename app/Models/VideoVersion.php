<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoVersion extends Model
{
    protected $fillable = ['movie_id', 'quality', 'filepath'];

    // Define the relationship with the Movie model
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
    use HasFactory;
}
