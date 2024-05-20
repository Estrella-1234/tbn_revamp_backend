<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/users', function (Request $request) {
    return $request->user();
});

Route::post('login', 'FrontendAuthController@login');
Route::post('register', 'FrontendRegisterController@register');
Route::get('/events', 'EventController@getAllEvents');
Route::get('/events/{id}', 'EventController@getEvent');


Route::get('/registrations', 'RegistrationController@getAllData');
Route::get('/registrations/{id}', 'RegistrationController@getRegistration');
Route::post('/registrations', 'RegistrationController@createRegistration');
Route::put('/registrations/{id}', 'RegistrationController@editRegistration');
Route::delete('/registrations/{id}', 'RegistrationController@deleteRegistration');


