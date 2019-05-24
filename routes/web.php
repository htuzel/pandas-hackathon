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

//Generic testing routes
Route::get('/test', 'TestController@test')->name('test');
Route::get('/test2', 'TestController@test2')->name('test2');



Route::get('/index', 'HomeController@index')->name('home');
Route::get('/results', 'SearchController@index')->name('results');
Route::get('/recommendations', 'SearchController@recommendations')->name('recommendations');
