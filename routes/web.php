<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatchController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [MatchController::class, 'index'])->name('home');

Route::prefix('club')->group(function () {
    Route::get('/', [MatchController::class, 'club'])->name('club');
    Route::post('/store', [MatchController::class, 'storeClub'])->name('club.store');
    Route::put('/update/{id}', [MatchController::class, 'updateClub'])->name('club.update');
    Route::delete('/destroy/{id}', [MatchController::class, 'destroyClub'])->name('club.destroy');
});

Route::prefix('score')->group(function () {
    Route::get('/', [MatchController::class, 'score'])->name('score');
    Route::post('/store', [MatchController::class, 'storeScore'])->name('score.store');
    Route::get('/show/{id}', [MatchController::class, 'showScore'])->name('score.show');
    Route::put('/update/{id}', [MatchController::class, 'updateScore'])->name('score.update');
    Route::delete('/destroy/{id}', [MatchController::class, 'destroyScore'])->name('score.destroy');
});