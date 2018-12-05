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
    $router->group([CONST_PREFIX => 'trolley'], function () use ($router) {
        $router->get('/{user_id}', 'v1\CartController@index');
    });    
    $router->group([CONST_PREFIX => 'cart'], function () use ($router) {
        $router->get('/{id}', 'v1\CartController@show');
        $router->post('/', 'v1\CartController@store');
        $router->put('/setpending/{invoice_id}/{user_id}', 'v1\CartController@setPending');
    });

    /**
     * Invoice routes
     */
    $router->group([CONST_PREFIX => 'invoice'], function () use ($router) {
        $router->get('/history/{user_id}', 'v1\InvoiceController@index');
        $router->get('/{id}', 'v1\InvoiceController@show');
        $router->post('/checkout', 'v1\InvoiceController@checkout');
        $router->put('/setlock/{id}', 'v1\InvoiceController@setLock');
    });
});
