<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    //Socialite
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),             // Your github Client ID
        'client_secret' => env('GITHUB_CLIENT_SECRET'),     // Your github Client Secret
        'redirect' => env('/login/github/callback'),
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),           // Your facebook Client ID
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),   // Your facebook Client Secret
        'redirect' => env('/login/facebook/callback'),
    ],

];
