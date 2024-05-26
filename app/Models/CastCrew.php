<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CastCrew extends Model
{
    protected $fillable = ['name', 'occupation', 'role'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
    use HasFactory;
}
