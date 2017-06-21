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

Auth::routes();

Route::get('/resident', 'HomeController@index')->name('resident');

Route::get('/resident/deleteResident/{residentId}', 'ResidentController@deleteResident');

Route::post('/resident/updateResident', 'ResidentController@updateResident');

Route::post('/resident/addResident', 'ResidentController@addResident');

Route::get('/door', 'DoorController@showView');

// Dummy
Route::get('/populate', 'ResidentController@populateDummy');
