<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::put('track/{activity?}/{user?}/{event?}', 'TrackController@update')->where('activity', '[A-Za-z0-9]{4,9}+')->where('user', '[0-9]+')->where('event', '(enter|click|exit)');
Route::get('activity/{activity?}', 'ActivityController@show')->where('activity', '[A-Za-z0-9]{4,9}+');
Route::get('user/{user?}', 'UserController@show')->where('user', '[0-9]+');
