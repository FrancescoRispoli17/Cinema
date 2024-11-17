<?php

use App\Http\Controllers\Api\CinemaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/programming', [CinemaController::class, 'programming']);
Route::get('/show', [CinemaController::class, 'show']);
Route::post('/reservation', [CinemaController::class, 'reservation']);
Route::post('/register', [CinemaController::class, 'register']);
Route::post('/login', [CinemaController::class, 'login']);
Route::get('/movies', [CinemaController::class, 'movies']);
Route::get('/movie', [CinemaController::class, 'movie']);

Route::middleware('auth:sanctum')->get('/user', [CinemaController::class, 'user']);
