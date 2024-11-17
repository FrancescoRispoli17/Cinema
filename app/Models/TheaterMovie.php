<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheaterMovie extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['occupy'];
    protected $table = 'theater_movie';
}
