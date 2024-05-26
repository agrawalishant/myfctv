<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';

// Front Routes
Route::
        namespace('App\Http\Controllers\Front')->group(function () {
            Route::get('/', 'HomeController@index')->name('home');
            Route::get('/movie/{id}', 'MovieController@show');
            Route::get('/movies/category/{categoryId}', 'MovieController@moviesByCategory');
            Route::get('/movies', 'MovieController@allMovie');
            Route::get('/series', 'SeriesController@allSeries');
            Route::get('series/{id}', 'SeriesController@show');
            Route::get('series/episode/{id}', 'SeriesController@episode');
            Route::match(['get', 'post'], '/login', 'AuthController@login')->name('login');
            Route::match(['get', 'post'], '/register', 'AuthController@register');
            Route::get('subscription-plan', 'SubscriptionController@show');
            Route::post('/subscribe', 'SubscriptionController@subscribe')->name('subscribe');
            Route::post('/handle-razorpay-payment', 'SubscriptionController@handleRazorpayPayment')->name('handle-razorpay-payment');
            Route::post('/check-subscription-status', 'SubscriptionController@checkSubscriptionStatus')->name('check-subscription-status');
            Route::match(['get','post'],'/reset-password','AuthController@forgotPassword');
            Route::match(['get','post'],'/change-password','AuthController@changePassword');
            Route::match(['get','post'],'/contact-us','ContactController@contact');
            Route::get('/upcoming','ComingsoonController@index');
            Route::get('/upcoming/{id}','ComingsoonController@single');
            Route::get('/search', 'SearchController@search');
            Route::get('/privacy-policy','PolicyController@privacyPolicy');
            Route::get('/terms-of-use','PolicyController@terms');
            Route::get('/refund-policy','PolicyController@refund');
            Route::get('/grievance','PolicyController@grievance');

            Route::middleware(['auth:user'])->group(function () {
                Route::get('/my-account', 'AuthController@account');
                Route::post('/update-profile', 'AuthController@updateProfile');
                Route::get('/logout', 'AuthController@logout')->name('logout');
                Route::post('/add-to-watchlist','WatchlistController@add');
                Route::get('/view-watchlist','WatchlistController@view');
                Route::get('/remove/{id}','WatchlistController@remove');
            });
        });

// Dashboard Routes
Route::
        namespace('App\Http\Controllers\Dashboard')->group(function () {
            Route::match(['get', 'post'], 'system-login', 'HomeController@login');

            Route::get('forgot-password', 'ForgotPasswordController@forgotPassword');
            Route::post('password-link', 'ForgotPasswordController@checkPassword');
            Route::get('reset-password/{token}', 'ForgotPasswordController@resetForm');
            Route::post('resetting-password', 'ForgotPasswordController@reset');
            Route::get('confirm-mail', 'ForgotPasswordController@confirm');

            Route::prefix('dashboard')->middleware(['auth:admin'])->group(function () {

                Route::get('/', 'HomeController@home');
                Route::get('logout', 'HomeController@logout');

                Route::get('user-list', 'UserController@user');
                Route::post('update-user-status', 'UserController@updateUserStatus');
                Route::get('delete-user/{id}', 'UserController@deleteUser');
                Route::get('/view-user/{id}', 'UserController@viewUser');

                Route::get('/movie/categories', 'MovieCategoryController@Category');
                Route::match(['get', 'post'], '/movie/categories/create', 'MovieCategoryController@addCategory');
                Route::match(['get', 'post'], '/movie/categories/edit/{id}', 'MovieCategoryController@editCategory');
                Route::get('/delete-mcat/{id}', 'MovieCategoryController@deletemcat');

                Route::get('/movie/list', 'MovieController@movieList');
                Route::post('/movie/upload', 'MovieController@movieUpload');
                Route::match(['get', 'post'], '/movie/edit/{id}', 'MovieController@movieEdit');
                Route::get('/movie-delete/{id}', 'MovieController@deleteMovie');

                Route::get('/movies/{id}/cast-crew', 'MovieController@showCastCrew');
                Route::post('/movies/{id}/cast-crew', 'MovieController@storeCastCrew');
                Route::get('/delete-cmem/{id}', 'MovieController@deletecmem');

                Route::get('/movies/{id}/upload', 'MovieController@upload');
                Route::post('/movies/{id}/upload', 'MovieController@uploadVideo');
                Route::get('/video-delete/{id}', 'MovieController@deleteVideo');

                Route::get('/series/list', 'SeriesController@list');
                Route::post('/series/upload', 'SeriesController@upload');
                Route::match(['get', 'post'], '/series/edit/{id}', 'SeriesController@editSeries');
                Route::get('delete-series/{id}', 'SeriesController@deleteSeries');

                Route::post('/series/create-season', 'SeriesController@createSeason');
                Route::get('/series/season/manage/{id}', 'SeriesController@manageSeason');
                Route::get('/series/delete-season/{id}', 'SeriesController@deleteSeason');

                Route::post('/series/season/create-episode', 'SeriesController@createEpisode');
                Route::post('/series/season/edit-episode/{id}', 'SeriesController@editEpisode');
                Route::get('/series/season/delete-episode/{id}', 'SeriesController@deleteEpisode');

                Route::post('/series/season/cast-crew', 'SeriesController@storeCastCrew');
                Route::get('/delete-mem/{id}', 'SeriesController@deletemem');

                Route::get('/slider', 'SliderController@index');
                Route::post('/slider-upload', 'SliderController@uploadSlider');
                Route::post('/slider-update/{id}', 'SliderController@updateSlider');
                Route::get('/delete-slider/{id}', 'SliderController@deleteSlider');

                Route::get('/subscription-plan', 'SubscriptionController@index');
                Route::post('/subscription-plan/add', 'SubscriptionController@add');
                Route::post('/subscription-plan/edit/{id}', 'SubscriptionController@edit');
                Route::get('subscription-plan/delete/{id}', 'SubscriptionController@delete');
                Route::get('/transaction', 'SubscriptionController@transaction');

                Route::get('/smtp-settings/add', 'SmtpSettingController@add');
                Route::get('/smtp-settings/edit', 'SmtpSettingController@edit');
                Route::post('/smtp-settings/update', 'SmtpSettingController@update');

                Route::get('/update-password', 'HomeController@updatePassword');
                Route::post('/check-current-pwd', 'HomeController@chkCurrentPassword');
                Route::post('/update-current-pwd', 'HomeController@updateCurrentPassword');
                Route::get('/profile', 'HomeController@Profile');
                Route::post('/update-admin-details', 'HomeController@updateAdminDetails');

                Route::get('/roles', 'HomeController@role');
                Route::post('/add-roles', 'HomeController@addRole');
                Route::post('/update-role-status', 'HomeController@updateRoleStatus');
                Route::get('/delete-role/{id}', 'HomeController@deleteRole');

                Route::get('/create-admin', 'AdminController@create');
                Route::post('/add-admin', 'AdminController@add');
                Route::get('/manage-admin', 'AdminController@manageAdmin');
                Route::post('/update-admin-status', 'AdminController@updateAdminStatus');
                Route::match(['get', 'post'], '/edit-admin/{id}', 'AdminController@editAdmin');
                Route::get('/delete-admin/{id}', 'AdminController@deleteAdmin'); //Pending
                Route::match(['get', 'post'], 'set-permission/{id}', 'AdminController@updateRole');

                Route::get('/coming-soon', 'ComingSoonController@index');
                Route::post('/coming-soon/upload', 'ComingSoonController@upload');
                Route::match(['get', 'post'], '/coming-soon/edit/{id}', 'ComingSoonController@edit');
                Route::get('delete-coming/{id}', 'ComingSoonController@delete');
            });
        });

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['admin']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});