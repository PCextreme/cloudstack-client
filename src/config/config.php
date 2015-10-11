<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudstack API Connection
    |--------------------------------------------------------------------------
    |
    | The Cloudstack API is the central point to interact with Cloudstack
    | using the user identifier and secret to sign request.
    |
    */

    'connection' => [

        'host'       => 'https://cloud.pcextreme.nl/client/api',
        'identifier' => '',
        'secret'     => '',

        /*
        |--------------------------------------------------------------------------
        | Single Sign On Key
        |--------------------------------------------------------------------------
        |
        | The single sign on key is used for signing client and console URIs.
        | This key is generally only available to Cloudstack administrators.
        |
        */

        'sso_key'     => '',

    ],

    /*
    |--------------------------------------------------------------------------
    | Client Endpoint
    |--------------------------------------------------------------------------
    |
    | The client endpoint is used for "single sign on" requests. Calling the
    | API with a SSO signed request enables the end user to directly login
    | without entering their password.
    |
    | Important: The SSO key is required for these kind of requests
    |
    */

    'client'  => 'https://cloud.pcextreme.nl/',

    /*
    |--------------------------------------------------------------------------
    | Console Endpoint
    |--------------------------------------------------------------------------
    |
    | The console endpoint is used for generating console endpoints with
    | the console proxy.
    |
    | Important: The SSO key is required for these kind of requests
    |
    */

    'console' => 'https://cloud.pcextreme.nl/client/console',

];
