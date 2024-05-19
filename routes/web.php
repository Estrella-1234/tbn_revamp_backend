<?php

use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('/auth/login') ;
});

// Authentication routes
Auth::routes(['register' => false]); // Disable registration

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/profile', 'ProfileController@index')->name('profile');


    Route::put('/profile', 'ProfileController@update')->name('profile.update');
    Route::get('/about', function () {
        return view('about');
    })->name('about');
    Route::get('/blog', 'PostController@index')->name('blog');
    Route::resource('users', 'UserController');
    Route::resource('events', 'EventController');

    Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::put('/registrations/{registration}/updateStatus', [RegistrationController::class, 'updateStatus'])->name('registrations.updateStatus');
    Route::resource('registrations', 'RegistrationController');


});

// Public route for testing
Route::get('/example', function () {
    return 'Hello, world!';
});





