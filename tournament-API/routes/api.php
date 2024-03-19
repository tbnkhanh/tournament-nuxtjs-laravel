<?php

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
    Route::get('/getAll', [TournamentController::class, 'index'])->name('tournament.index');
    Route::post('/create', [TournamentController::class, 'store'])->name('tournament.store');
    Route::post('/update', [TournamentController::class, 'update'])->name('tournament.update');
    Route::delete('/delete/{id}', [TournamentController::class, 'destroy'])->name('tournament.destroy');
});