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

Route::get('/', 'HomeController@index')->name('home');

/** SOCIALITE **/
Route::get('login/{driver}', 'Auth\LoginController@redirectToProvider')->name('social_auth');
Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback');


/** AUTH **/
Auth::routes();

/** LANGUAGES **/
Route::get('/set_language/{lang}', 'Controller@setLanguage')->name('set_language');


/** IMAGES **/
Route::get('/images/{path}/{attachment}', function ($path, $attachment){
    $file = sprintf('storage/%s/%s', $path, $attachment);
    if (File::exists($file))
        return Image::make($file)->response();
});


/** COURSES **/
Route::prefix('courses')->group( function(){
    Route::get('/{course}', 'CourseController@show')->name('courses.detail');
});


/** SUBSCRIPTIONS **/
Route::prefix('subscriptions')->group(function (){
    Route::get('/plans', 'SubscriptionController@plans')->name('subscriptions.plans');
    Route::post('/process_subscription', 'SubscriptionController@processSubscription')
        ->name('subscriptions.process_subscriptions');
});
