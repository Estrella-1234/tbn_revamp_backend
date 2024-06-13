<?php

use App\Http\Controllers\ReviewsController;
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
Route::post('/google-auth', 'FrontendRegisterController@googleAuth');
Route::get('/events', 'EventController@getAllEvents');
Route::get('/events/{id}', 'EventController@getbyId');
Route::get('events/details/{slug}', 'EventController@getbySlug')
    ->where('slug', '[A-Za-z0-9\-]+')
    ->name('events.show');



Route::get('/registrations', 'RegistrationController@getAllData');
Route::get('/registrations/{id}', 'RegistrationController@getRegistration');
Route::post('/registrations', 'RegistrationController@createRegistration');
Route::put('/registrations/{id}', 'RegistrationController@editRegistration');
Route::delete('/registrations/{id}', 'RegistrationController@deleteRegistration');

Route::post('/reviews/{registration}', 'ReviewsController@addReview');
Route::put('/reviews/{review}', 'ReviewsController@updateReview');
Route::delete('/reviews/{review}', 'ReviewsController@deleteReview');
Route::get('/reviews', 'ReviewsController@getAllReviews');
Route::get('/reviews/{id}', 'ReviewsController@getReviewbyId');

Route::get('blogs', 'BlogController@getAll');

Route::get('blogs/details/{slug}', 'BlogController@getBySlug')
    ->where('slug', '[A-Za-z0-9\-]+')
    ->name('blogs.show');

Route::get('blogs/{id}', 'BlogController@getById');

//Route::post('blogs', 'BlogController@createF');
//Route::put('blogs/{id}', 'BlogController@updateF');
//Route::delete('blogs/{id}', 'BlogController@deleteF');

Route::get('blogs/{blog}/comments', 'CommentController@getallComments');
Route::get('comments/{id}', 'CommentController@getComment');
Route::get('users/{id}/comments', 'CommentController@getUserComment');
Route::post('blogs/{blog}/comments', 'CommentController@createComment');
Route::put('comments/{comment}', 'CommentController@editComment');
Route::delete('comments/{comment}', 'CommentController@deleteF');

Route::get('posts', 'PostController@getAllPost');
Route::get('partners', 'PartnerController@getallpartners');







