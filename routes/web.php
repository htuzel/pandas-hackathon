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


//Generic testing routes
Route::get('/test', 'TestController@test')->name('test');
Route::get('/test2', 'TestController@test2')->name('test2');
Route::get('/test3', 'TestController@test3')->name('test3');

//API
Route::get('/projects', 'ProjectController@projects')->name('projects');
Route::get('/users', 'ProjectController@users')->name('users');
Route::get('/components', 'ProjectController@components')->name('components');
Route::get('/issues', 'ProjectController@issues')->name('issues');


Route::get('/', 'HomeController@index')->name('home');
Route::post('/search', 'SearchController@search')->name('search');
Route::get('/results', 'SearchController@index')->name('results');
Route::get('/recommendations', 'SearchController@recommendations')->name('recommendations');
