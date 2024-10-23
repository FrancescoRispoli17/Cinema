<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    public function theaters(){
        return $this->belongsToMany(Theater::class, 'theater_movie');
    }
}
