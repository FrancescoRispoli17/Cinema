<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=config('movies');
        
        foreach($data as $movie){
            $movie_db=new Movie();

            $movie_db->title=$movie['title'];
            $movie_db->thumb=$movie['thumb'];
            $movie_db->description=$movie['description'];
            $movie_db->date=$movie['date'];
            $movie_db->director=$movie['director'];
            $movie_db->country=$movie['country'];
            $movie_db->duration=$movie['duration'];

            $movie_db->save();
        }
    }
}
