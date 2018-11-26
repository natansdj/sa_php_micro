<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

if ( ! defined('CONST_PREFIX')) {
    define('CONST_PREFIX', 'prefix');
}

/** @var \Laravel\Lumen\Routing\Router $router */
$router->group([CONST_PREFIX => 'api/v1'], function () use ($router) {
    /**
     * Cart routes
     */
    $router->group([CONST_PREFIX => 'cart'], function () use ($router) {
        $router->get('/', 'v1\CartController@index');
        $router->get('/{id}', 'v1\CartController@show');
    });

    /**
     * Invoice routes
     */
    $router->group([CONST_PREFIX => 'invoice'], function () use ($router) {
        $router->get('/', 'v1\InvoiceController@index');
        $router->get('/{id}', 'v1\InvoiceController@show');
    });
});
