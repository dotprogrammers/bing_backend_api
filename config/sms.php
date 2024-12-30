<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS API Status
    |--------------------------------------------------------------------------
    |
    | Set to true to enable SMS functionality. If false, SMS will not be sent.
    |
    */
    'status' => env('SMS_API_STATUS', true),

    /*
    |--------------------------------------------------------------------------
    | SMS API URL
    |--------------------------------------------------------------------------
    |
    | The base URL for your SMS API. Make sure to set this in your .env file.
    |
    */
    'api_url' => env('SMS_API_URL', 'https://msg.elitbuzz-bd.com/smsapi'),

    /*
    |--------------------------------------------------------------------------
    | Default Sender ID
    |--------------------------------------------------------------------------
    |
    | The sender ID used for sending SMS. Replace '*' with your desired ID
    | or configure multiple IDs.
    |
    */
    'sender_id' => env('SMS_SENDER_ID', '8809601012446'),

    /*
    |--------------------------------------------------------------------------
    | Authentication Token
    |--------------------------------------------------------------------------
    |
    | Token for authenticating requests to the SMS API. You can set multiple
    | tokens for different environments or users.
    |
    */
    'token' => [
        'default' => env('SMS_API_TOKEN', 'C2009298676addda194148.52557430'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Additional Settings
    |--------------------------------------------------------------------------
    |
    | Define any additional settings for the SMS API integration.
    |
    */
    'timeout' => env('SMS_API_TIMEOUT', 30), // Timeout in seconds for API requests
];
