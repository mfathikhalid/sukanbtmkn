<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BowlingController;
use App\Http\Controllers\DartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\PublicLiveController;
use App\Http\Controllers\PublicParticipantController;
use App\Http\Controllers\PublicRegistrationController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ScoreboardController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'public.home')->name('home');

Route::get('/live', PublicLiveController::class)->name('live.index');
Route::view('/jadual', 'public.schedule')->name('schedule.index');
Route::get('/peserta', PublicParticipantController::class)->name('public-participants.index');
Route::get('/daftar', [PublicRegistrationController::class, 'create'])->name('public-registration.create');
Route::post('/daftar', [PublicRegistrationController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('public-registration.store');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('participants', ParticipantController::class)->except(['show']);
    Route::resource('registrations', RegistrationController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::get('/fifa', [EventController::class, 'show'])->defaults('sportName', 'FIFA')->name('events.fifa');
    Route::get('/tekken', [EventController::class, 'show'])->defaults('sportName', 'Tekken')->name('events.tekken');
    Route::get('/pickleball', [EventController::class, 'show'])->defaults('sportName', 'Pickleball')->name('events.pickleball');
    Route::get('/congkak', [EventController::class, 'show'])->defaults('sportName', 'Congkak')->name('events.congkak');
    Route::get('/carrom', [EventController::class, 'show'])->defaults('sportName', 'Carrom')->name('events.carrom');
    Route::get('/dart', [DartController::class, 'index'])->name('dart.index');
    Route::post('/dart/{match}/result', [DartController::class, 'store'])->name('dart.results.store');
    Route::put('/matches/{match}', [MatchController::class, 'update'])->name('matches.update');
    Route::post('/matches/{sport}/league-fixtures', [MatchController::class, 'leagueFixtures'])->name('matches.league-fixtures');
    Route::post('/matches/{sport}/semi-finals', [MatchController::class, 'semiFinals'])->name('matches.semi-finals');
    Route::post('/matches/{sport}/finals', [MatchController::class, 'finals'])->name('matches.finals');
    Route::post('/matches/{sport}/third-place', [MatchController::class, 'thirdPlace'])->name('matches.third-place');
    Route::delete('/matches', [MatchController::class, 'reset'])->name('matches.reset');
    Route::get('/bowling', [BowlingController::class, 'index'])->name('bowling.index');
    Route::post('/bowling', [BowlingController::class, 'store'])->name('bowling.store');
    Route::delete('/bowling/scores', [BowlingController::class, 'reset'])->name('bowling.reset');
    Route::get('/scoreboard', [ScoreboardController::class, 'index'])->name('scoreboard.index');
});
