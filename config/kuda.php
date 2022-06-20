<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Kuda Api Keys
    |--------------------------------------------------------------------------
    |
    | Get your generated kuda bank access tokens from your kuda developer
    | account at https://developer.kuda.com/account/api-keys
    |
    |
    */

    'private_key' => env('KUDA_PRIVATE_KEY'),
    'public_key' => env('KUDA_PUBLIC_KEY', ''),
    'client_key' => env('KUDA_CLIENT_KEY', 'CGZFw5lPQbMaXf3Y426v'),
    'base_url' => env('KUDA_BASE_URL', '​https://kuda-openapi.kuda.com/v1​')



];