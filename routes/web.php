<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReviewsController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('/auth/login') ;
});


// Authentication routes
Auth::routes(['register' => false]);


// Public route for testing
Route::get('/example', function () {
    return 'Hello, world!';
});

Route::get('/clear', function () {
    // Run artisan command programmatically
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return 'Caches cleared successfully.';
});

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');
    Route::get('/about', function () {
        return view('about');
    })->name('about');

    // User routes
    Route::resource('users', 'UserController');

    // Event routes
    Route::resource('events', 'EventController');
    Route::get('events/{slug}', 'EventController@show')
        ->where('slug', '[A-Za-z0-9\-]+')
        ->name('events.show');

// Registration routes
    Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::patch('/registrations/{registration}/status', [RegistrationController::class, 'updateStatus'])->name('registrations.updateStatus');
    Route::patch('/registrations/{id}/attendance', [RegistrationController::class, 'updateAttendance']);
    Route::get('/registrations/export/form', [RegistrationController::class, 'showExportForm'])->name('registrations.exportForm');
    Route::get('/registrations/export', [RegistrationController::class, 'export'])->name('registrations.export');
    Route::resource('registrations', 'RegistrationController');


    // Review routes
    Route::get('/reviews', [ReviewsController::class, 'index'])->name('reviews.index');
    Route::resource('reviews', 'ReviewsController');
    Route::get('registrations/{registration}/reviews/create', [ReviewsController::class, 'create'])->name('reviews.create');
    Route::post('registrations/{registration}/reviews', [ReviewsController::class, 'store'])->name('reviews.store');

    // Blog routes
    Route::get('/blog', 'PostController@index')->name('blog');
    Route::resource('blogs', 'BlogController');
    Route::get('blogs/{slug}', 'App\Http\Controllers\BlogController@show')->name('blogs.show');
    Route::get('blogs/{blog}/comments', 'CommentController@index')->name('comments.index');
    Route::post('blogs/{blog}/comments','CommentController@store')->name('comments.store');
    Route::delete('comments/{comment}','CommentController@destroy')->name('comments.destroy');

    // Post routes
    Route::resource('posts', 'PostController');

    // Partner routes
    Route::resource('partners', 'PartnerController');

});

// Public route for testing
Route::get('/example', function () {
    return 'Hello, world!';
});







