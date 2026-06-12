<?php

use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TennisMatchController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);

    return redirect()->back();
})->name('locale.switch');

Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');
Route::get('/tournaments/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');

Route::middleware('auth')->group(function () {

    Route::get('/admin/users', [PlayerController::class, 'index'])->name('admin.users.index');
    Route::put('/admin/users/{user}/role', [PlayerController::class, 'updateRole'])->name('admin.users.role');
    Route::patch('/admin/users/{id}/toggle-block', [PlayerController::class, 'toggleBlock'])->name('admin.users.toggleBlock');

    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/players/{user}', [PlayerController::class, 'show'])->name('players.show');

    Route::get('/tournaments/{tournament}/matches/create', [TennisMatchController::class, 'create'])->name('tennisMatches.create');
    Route::post('/tournaments/{tournament}/matches', [TennisMatchController::class, 'store'])->name('tennisMatches.store');

    Route::post('/tournaments/{tournament}/register', [RegistrationController::class, 'store'])->name('tournaments.register');
    Route::put('/tournaments/{tournament}/players/{userId}', [RegistrationController::class, 'update'])->name('tournaments.registration.update');

    Route::get('/tournaments-create', [TournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{tournament}/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
    Route::put('/tournaments/{tournament}', [TournamentController::class, 'update'])->name('tournaments.update');
    Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
});

require __DIR__ . '/auth.php';
