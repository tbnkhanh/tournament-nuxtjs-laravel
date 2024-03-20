<?php

use App\Http\Controllers\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TournamentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("tournament")->group(function () {
    Route::get('/getAll', [TournamentController::class, 'index']);
    Route::get('/findById/{id}', [TournamentController::class, 'findById']);
    Route::post('/create', [TournamentController::class, 'store']);
    Route::post('/update', [TournamentController::class, 'update']);
    Route::delete('/delete/{id}', [TournamentController::class, 'destroy']);
});

Route::prefix("team")->group(function () {
    Route::get('/getTeamsWithPlayers/{tournamentId}', [TeamController::class, 'getTeamsWithPlayers']);
    Route::post('/create', [TeamController::class, 'store']);
    Route::delete('/delete/{id}', [TeamController::class, 'destroy']);
});