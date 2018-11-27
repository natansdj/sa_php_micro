<?php

return [
    'default' => env('CACHE_DRIVER', CONST_REDIS),

    'stores' => [
        'file'      => [
            'driver' => 'file',
            'path'   => storage_path('framework/cache'),
        ],
        CONST_REDIS => [
            'driver'     => CONST_REDIS,
            'connection' => 'default',
        ],
    ],

    'prefix' => env(
        'CACHE_PREFIX',
        str_slug(env('APP_NAME', 'laravel'), '_') . '_cache'
    ),
];
