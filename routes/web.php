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

//API
Route::get('/projects', 'ProjectController@projects')->name('projects');
Route::get('/users', 'ProjectController@users')->name('users');
Route::get('/components', 'ProjectController@componentNames')->name('components');
Route::get('/issues', 'ProjectController@issues')->name('issues');


Route::get('/', 'HomeController@index')->name('home');
Route::post('/search', 'SearchController@search')->name('search');
Route::post('/estimation', 'SearchController@estimation')->name('estimation');
Route::get('/recommendations', 'SearchController@recommendations')->name('recommendations');
