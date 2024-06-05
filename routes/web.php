<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReviewsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('/auth/login') ;
});


// Authentication routes
//Auth::routes(['register' => false]); // Disable registration
Auth::routes();

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
    Route::get('registrations/export', [RegistrationController::class, 'export'])->name('registrations.export');
    Route::put('/registrations/{registration}/updateStatus', [RegistrationController::class, 'updateStatus'])->name('registrations.updateStatus');
    Route::resource('registrations', 'RegistrationController');

//    Route::resource('reviews', ReviewsController::class);
    Route::get('/reviews', [ReviewsController::class, 'index'])->name('reviews.index');
    Route::resource('reviews', 'ReviewsController');

// Route to create a review for a specific registration

    Route::get('registrations/{registration}/reviews/create', [ReviewsController::class, 'create'])->name('reviews.create');
    Route::post('registrations/{registration}/reviews', [ReviewsController::class, 'store'])->name('reviews.store');


    Route::resource('blogs', 'BlogController');


    Route::get('blogs/{blog}/comments', 'CommentController@index')->name('comments.index');
    Route::post('blogs/{blog}/comments','CommentController@store')->name('comments.store');
    Route::delete('comments/{comment}','CommentController@destroy')->name('comments.destroy');

    Route::resource('posts', 'PostController');

    Route::resource('partners', 'PartnerController');

});

// Public route for testing
Route::get('/example', function () {
    return 'Hello, world!';
});






