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

Route::get('/salesmen_cashin', 'HomeController@salesmen_cashin');
Route::get('/credit_transactions', 'HomeController@credit_transactions');

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
    Route::middleware(['auth'])->group(function () {
        Route::get('/subscribed', 'CourseController@subscribed')->name('courses.subscribed');
        Route::get('/{course}/inscribe', 'CourseController@inscribe')->name('courses.inscribe');
        Route::post('/add_review', 'CourseController@addReview')->name('courses.add_review');

        Route::middleware([sprintf("role:%s", \App\Role::TEACHER)])->group(function (){
            Route::get('/create', 'CourseController@create')->name('courses.create');
            Route::post('/store', 'CourseController@store')->name('courses.store');
            Route::put('/{course}/update', 'CourseController@update')->name('courses.update');
        });
    });

    Route::get('/{course}', 'CourseController@show')->name('courses.detail');
});


Route::middleware(['auth'])->group(function () {
    /** SUBSCRIPTIONS **/
    Route::prefix('subscriptions')->group(function (){
        Route::get('/plans', 'SubscriptionController@plans')->name('subscriptions.plans');
        Route::get('/admin', 'SubscriptionController@admin')->name('subscriptions.admin');
        Route::post('/process_subscription', 'SubscriptionController@processSubscription')
            ->name('subscriptions.process_subscriptions');
        Route::post('/resume', 'SubscriptionController@resume')->name('subscriptions.resume');
        Route::post('/cancel', 'SubscriptionController@cancel')->name('subscriptions.cancel');
    });

    /** INVOICES **/
    Route::prefix('invoices')->group(function (){
        Route::get('/admin', 'InvoiceController@admin')->name('invoices.admin');
        Route::get('/{invoice}/download', 'InvoiceController@download')->name('invoices.download');
    });

    /** PROFILE **/
    Route::prefix('profile')->group(function (){
        Route::get('/', 'ProfileController@index')->name('profile.index');
        Route::put('/', 'ProfileController@update')->name('profile.update');
    });

    /** SOLICITUDE **/
    Route::prefix('solicitude')->group(function (){
        Route::post('/teacher', 'SolicitudeController@teacher')->name('solicitude.teacher');
    });

    /** TEACHERS **/
    Route::prefix('teacher')->group(function(){
        Route::get('/courses', 'TeacherController@courses')->name('teacher.courses');
        Route::get('/students', 'TeacherController@students')->name('teacher.students');
        Route::post('/send_message_to_student', 'TeacherController@sendMessageToStudent')
            ->name('teacher.send_message_to_student');
    });


    Route::get('/subscribed', 'CourseController@subscribed')->name('courses.subscribed');
    Route::get('/{course}/inscribe', 'CourseController@inscribe')->name('courses.inscribe');
    Route::post('/add_review', 'CourseController@addReview')->name('courses.add_review');
});


