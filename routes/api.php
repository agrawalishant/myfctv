<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::
        namespace('App\Http\Controllers\User')->group(function () {

            // List all series
            Route::get('/series', 'SeriesController@index');
            // Show a specific series
            Route::get('/series/{id}', 'SeriesController@show');


            // List All Categories
            Route::get('/category', 'MovieController@category');
            // List Movie Under Category
            Route::get('/category/{categoryId}/movies', 'MovieController@moviesUnderCategory');
            // View Movie Details
            Route::get('/category/{categoryId}/movies/{movieId}', 'MovieController@viewMovieDetails');
            
             // List movies and series under Category
            Route::get('/category/all-data','MovieController@allData');

            // Get Slider
            Route::get('/sliders', 'SliderController@index');

            // Coming Soon
            Route::get('/coming-soon','ComingSoonController@allData');

            // Auth
            Route::post('login', 'AuthController@login');
            Route::post('register', 'AuthController@register');
            Route::get('/verify/{code}', 'AuthController@verify');
            Route::post('/resend-verification-email', 'AuthController@resendVerificationEmail');
            Route::post('/forgot-password','AuthController@forgotPassword');
            Route::post('/password-reset','AuthController@resetPassword');

            // Subscription Plan
            Route::get('/subscription-plans','SubscriptionController@index');
            

            Route::group(['middleware' => 'auth:api'], function () {
                // Your API routes, including the profile update route                
                Route::get('/profile', 'AuthController@profile');
                Route::post('/edit-profile', 'AuthController@editProfile');
                Route::post('/logout', 'AuthController@logout');

                Route::post('/watchlist/{type}/{itemId}', 'WatchlistController@addToWatchlist')->where('type', 'movie|series');
                Route::get('/watchlist', 'WatchlistController@getWatchlist');
                Route::get('/watchlist/{id}', 'WatchlistController@removeFromWatchlist');
                Route::post('/subscribe','SubscriptionController@subscribe');
                Route::get('/subscription/status', 'SubscriptionController@checkSubscriptionStatus');
            });

        });
