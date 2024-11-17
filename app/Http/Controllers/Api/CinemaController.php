<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Movie;
use App\Models\Reservation;
use App\Models\Theater;
use App\Models\TheaterMovie;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rules;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Http\Request;

class CinemaController extends Controller
{
    public function programming(Request $request)
    {
        // $today = date('Y-m-d');

        $results = Movie::select('theater_movie.date as day', 'movies.*', 'theater_movie.time as time')
            ->join('theater_movie', 'movies.id', '=', 'theater_movie.movie_id')
            ->join('theaters', 'theater_movie.theater_id', '=', 'theaters.id')
            // ->whereDate('theater_movie.date', '>=', $today)
            ->whereDate('theater_movie.date', '>=', '2024-10-25')
            ->orderBy('day')
            ->orderBy('theater_movie.time')
            ->get();

        $groupedResults = [];
        
        foreach ($results as $result) {
            if (!isset($groupedResults[$result->day])) {
                $groupedResults[$result->day] = [
                    'date' => $result->day,
                    'spettacoli' => []
                ];
            }

            $spettacoloIndex = array_search($result->title, array_column($groupedResults[$result->day]['spettacoli'], 'title'));
            
            if ($spettacoloIndex === false) {
                $groupedResults[$result->day]['spettacoli'][] = [
                    'title' => $result->title,
                    'thumb'=> $result->thumb,
                    'advertise' => $result->advertisement,
                    'description'=> $result->description,
                    'director'=> $result->director,
                    'orari' => [$result->time]
                ];
            } else {
                $groupedResults[$result->day]['spettacoli'][$spettacoloIndex]['orari'][] = $result->time;
            }
        }

        $groupedResults = array_values($groupedResults);

        return response()->json([
            'status' => true,
            'results' => $groupedResults
        ]);
    }

    public function show(Request $request){
        $results = Movie::select('theater_movie.date as day','theaters.*', 'movies.*', 'theater_movie.time as time','theater_movie.occupy')
            ->join('theater_movie', 'movies.id', '=', 'theater_movie.movie_id')
            ->join('theaters', 'theater_movie.theater_id', '=', 'theaters.id')
            ->whereDate('theater_movie.date', '=', $request->date)
            ->where('movies.title',$request->title)
            ->where('theater_movie.time',$request->time)
            ->get();

            return response()->json([
                'status' => true,
                'results' => $results
            ]);
    }

    public function movies(){
        $results = Movie::all();

            return response()->json([
                'status' => true,
                'results' => $results
            ]);
    }

    public function movie(Request $request){
        $movie=Movie::where('title',$request->title)->get();

        $results = TheaterMovie::select('theater_movie.*')
            ->join('movies', 'theater_movie.movie_id', '=', 'movies.id')
            ->where('movies.title',$request->title)
            ->get();

        $groupedResults = [];
        
        foreach ($results as $result) {
            if (!isset($groupedResults[$result->date])) {
                $groupedResults[$result->date] = [
                    'date' => $result->date,
                    'spettacoli' => []
                ];
            }

            // $spettacoloIndex = array_search($result->title, array_column($groupedResults[$result->day]['spettacoli'], 'title'));

            $groupedResults[$result->date]['spettacoli'][] =
                $result->time;
        }

         $groupedResults = array_values($groupedResults);

            return response()->json([
                'status' => true,
                'movie' => $movie,
                'results' => $groupedResults
            ]);
    }

    public function reservation(Request $request){
        $occupyString = '';
        if ($request->has('reserve') && is_array($request->reserve)) {
            $occupyString = implode(',', $request->reserve);

            $occupy = TheaterMovie::join('movies', 'theater_movie.movie_id', '=', 'movies.id')
                ->join('theaters', 'theater_movie.theater_id', '=', 'theaters.id')
                ->whereDate('theater_movie.date', '=', $request->date)
                ->where('theater_movie.time', $request->time)
                ->where('movies.title', $request->title)
                ->value('theater_movie.occupy');

            if($occupy)
                $occupy=$occupy.','. $occupyString;
            else
                $occupy=$occupyString;
    
            $updatedRows = TheaterMovie::join('movies', 'theater_movie.movie_id', '=', 'movies.id')
                ->join('theaters', 'theater_movie.theater_id', '=', 'theaters.id')
                ->whereDate('theater_movie.date', '=', $request->date)
                ->where('theater_movie.time', $request->time)
                ->where('movies.title', $request->title)
                ->update(['theater_movie.occupy' => $occupy]);
        }

        $reservation = new Reservation();
        $reservation->title = $request->title;
        $reservation->reservation = $occupyString;
        $reservation->date = $request->date;
        $reservation->time = $request->time;
        $reservation->room = $request->room;
        $reservation->user_id = $request->userID;
        $reservation->save();
    }

    public function register(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user()->load('reservations');

        return response()->json([
            'status' => true,
            'user' => $user
        ]);
    }

    public function login(LoginRequest $request){

        $user = $request->authenticate();
        
        $user = $request->user();

        $request->session()->regenerate();
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    

}
