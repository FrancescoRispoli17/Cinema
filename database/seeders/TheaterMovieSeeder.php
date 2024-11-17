<?php

namespace Database\Seeders;

use App\Models\TheaterMovie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TheaterMovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=config('shows');
        
        foreach($data as $movie){
            $movie_db=new TheaterMovie();

            $movie_db->theater_id=$movie['theater_id'];
            $movie_db->movie_id=$movie['movie_id'];
            $movie_db->date=$movie['date'];
            $movie_db->time=$movie['time'];
            $movie_db->language=$movie['language'];

            $movie_db->save();
        }
    }
}
