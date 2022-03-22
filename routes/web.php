<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// // game routes
// Route::name('games.')
//     ->namespace('Packages\GameDice\Http\Controllers\Frontend')
//     ->middleware(['web', 'auth', 'active', 'email_verified', '2fa']) // it's important to add web middleware as it's not automatically added for packages routes
//     ->group(function () {
//         // show initial game screen
//         Route::get('games/dice', 'GameDiceController@show')->name('dice.show');
//         // play game
//         Route::post('games/dice/play', 'GameDiceController@play')->name('dice.play');
//     });
Auth::routes();

// Route::get('/dice', [App\Http\Controllers\HomeController::class, 'index'])->name('dice');
Route::get('/dice', [App\Http\Controllers\DiceGameController::class, 'index'])->name('home');
