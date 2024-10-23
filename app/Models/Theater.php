<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theater extends Model
{
    use HasFactory;
    public function movies(){
        return $this->belongsToMany(Movie::class, 'theater_movie');
    }
}
