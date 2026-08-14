<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\RoomController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/quick-play', [LoginController::class, 'quickPlay'])->name('quick.play');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/player/{user}', [AdminController::class, 'playerDetail'])->name('player.detail');
        Route::delete('/score/{score}', [AdminController::class, 'deleteScore'])->name('score.delete');
        Route::delete('/player/{user}/reset', [AdminController::class, 'resetPlayer'])->name('player.reset');
        Route::delete('/player/{user}/delete', [AdminController::class, 'deletePlayer'])->name('player.delete');
        Route::patch('/score/{score}/toggle-hide', [AdminController::class, 'toggleHideScore'])->name('score.toggle-hide');
        // Room management
        Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
    });

// Player (Game) Routes
Route::middleware(['auth', 'role:player'])
    ->prefix('game')
    ->name('game.')
    ->group(function () {
        Route::get('/', [GameController::class, 'index'])->name('index');
        Route::post('/score', [GameController::class, 'saveScore'])->name('score.save');
        Route::get('/history', [GameController::class, 'history'])->name('history');
        Route::get('/complete', [GameController::class, 'complete'])->name('complete');
    });
