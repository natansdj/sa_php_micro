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
        $router->post('/update/{id}', 'v1\CartController@update');
        $router->get('/delete/{id}', 'v1\CartController@delete');
    });

    /**
     * Invoice routes
     */
    $router->group([CONST_PREFIX => 'invoice'], function () use ($router) {
        $router->get('/history/{user_id}', 'v1\InvoiceController@index');
        $router->get('/{id}', 'v1\InvoiceController@show');
    });

    /**
     * Book routes
     */
    $router->group([CONST_PREFIX => 'book'], function () use ($router) {
        $router->get('/checkout/{user_id}', 'v1\BookController@checkout');
        $router->put('/commit/{id}', 'v1\BookController@commit');
    });
});
