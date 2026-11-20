<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peran extends Model
{
    use HasFactory;

    protected $fillable = ['actor', 'cast_id', 'film_id'];

    public function cast()
    {
        return $this->belongsTo(Cast::class);
    }

    public function film()
    {
        return $this->belongsTo(Film::class);
    }
}