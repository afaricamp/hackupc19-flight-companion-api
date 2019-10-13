<?php

use Illuminate\Http\Request;

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

Route::post('login', 'GameController@login')->name('api.game.login');
Route::post('top5', 'GameController@top5')->name('api.game.top5');
Route::post('endgame', 'GameController@endgame')->name('api.game.endgame');
Route::post('flightscores', 'GameController@flightScores')->name('api.game.flight.scores');
Route::post('airportscores', 'GameController@airportScores')->name('api.game.airport.scores');

