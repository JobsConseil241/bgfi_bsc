<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'msg91' => [
        'auth_key' => env('MSG91_AUTH_KEY'),
        'sender_id' => env('MSG91_SENDER_ID'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'bulksms' => [
        'url' => env('BULKSMS_BASE_URL', 'https://api.bulksms.com/v1'),
        'token' => env('BULKSMS_TOKEN'),
    ],

    'airtel' => [
        'base_url' => env('AIRTEL_BASE_URL', 'https://messaging.airtel.ga:9002/smshttp/qs/'),
        'username' => env('AIRTEL_USERNAME', 'BGFI'),
        'password' => env('AIRTEL_PASSWORD'),
        'origin_addr' => env('AIRTEL_ORIGIN_ADDR', 'BGFI'),
    ],

    'otp' => [
        'length' => env('OTP_LENGTH', 6),
        'expiry_minutes' => env('OTP_EXPIRY_MINUTES', 10),
        'max_attempts' => env('OTP_MAX_ATTEMPTS', 3),
    ],

];
