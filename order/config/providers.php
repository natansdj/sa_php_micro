<?php
return [
    /*
     * Providers always load in Core
     */
    'global'     => [
        'core'    => \Core\Providers\CoreServiceProvider::class,
        'app'     => App\Providers\AppServiceProvider::class,
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
        //
    ],
];