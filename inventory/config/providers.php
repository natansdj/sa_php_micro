<?php
return [
    /*
     * Providers always load in Core
     */
    'global'     => [
        'core'    => \Core\Providers\CoreServiceProvider::class,
        'auth'    => \Core\Providers\AuthServiceProvider::class,
        'graphql' => \Core\Providers\GraphQLServiceProvider::class,
        'app'     => App\Providers\AppServiceProvider::class,
        \Intervention\Image\ImageServiceProvider::class,
    ],

    /*
     * Providers load when env is production
     */
    'production' => [
        //
    ],

    /*
     * Providers load when env is local
     */
    'local'      => [
        //
    ],

    'alias' => [
        'Image' => Intervention\Image\Facades\Image::class,
    ],
];