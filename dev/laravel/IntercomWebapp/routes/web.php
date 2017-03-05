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


Route::get('/', function () { return view('welcome'); });


// Aussensprechstelle
Route::get('/server', 'AppController@server');
Route::get('/client', 'AppController@client');
Route::get('/clientTest', 'AppController@clientTest');
