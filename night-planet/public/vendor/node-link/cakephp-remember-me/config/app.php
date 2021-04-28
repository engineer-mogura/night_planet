<?php

return [

    'Security' => [
        'cookieKey' => env('SECURITY_COOKIE_KEY', env('SECURITY_SALT', 'acf7cd3579e3765df27308099e929bbe8e25f653d00eef67ce22d2a4aeaefa99')),
    ],

    'RememberMe' => [
        'field' => 'remember_me',
        'cookie' => [
            'name' => 'remember_me',
            'expires' => '+1 year',
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httpOnly' => true,
        ],
    ],

];
