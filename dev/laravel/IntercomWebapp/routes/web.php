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


Route::get('/', function () { return view('welcome'); }); // TODO: Delete this

/** DOOR WEBAPP */
Route::get('/door', 'DoorController@showView');
Route::get('/ajaxListener/playGong/{residentId}/{doorId}', 'DoorController@playGong');

/** CLIENT WEBAPP */
Route::get('/client', 'ClientController@showView');
Route::get('/ajaxListener/openDoor/{id}', 'ClientController@openDoor');
Route::get('/ajaxListener/checkNotification/{userId}', 'ClientController@checkNotification');
Route::get('/ajaxListener/clearNotification/{userId}', 'ClientController@clearNotification');
