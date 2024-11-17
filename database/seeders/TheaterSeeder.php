<?php

namespace Database\Seeders;

use App\Models\Theater;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TheaterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=config('theaters');
        
        foreach($data as $theater){
            $theater_db=new Theater();

            $theater_db->number=$theater['number'];
            $theater_db->dolbyatmos=$theater['dolbyatmos'];
            $theater_db->XL=$theater['XL'];
            $theater_db->lines=$theater['line'];
            $theater_db->seats=$theater['seats'];

            $theater_db->save();
        }
    }
}
