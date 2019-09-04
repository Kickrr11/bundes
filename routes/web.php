<?php

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

Route::get('matches/upcoming', 'MatchesController@upcoming');
Route::get('matches/current-season', 'MatchesController@matchesCurrentSeason');
Route::get('teams/win-loss-ratio', 'TeamsController@winLossRatio');
Route::get('search/player-goals', 'SearchController@searchPlayerGoals');
Route::post('search/player-goals', 'SearchController@searchPlayer')->name('search.player.goals');

