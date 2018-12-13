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
     * Category routes
     */
    $router->group([CONST_PREFIX => 'category'], function () use ($router) {
        $router->get('/', 'v1\CategoryController@index');
        $router->get('/{id}', 'v1\CategoryController@show');
        $router->post('/', 'v1\CategoryController@store');
    });

    /**
     * Product routes
     */
    $router->group([CONST_PREFIX => 'product'], function () use ($router) {
        $router->get('/', 'v1\ProductController@index');
        $router->get('/{id}', 'v1\ProductController@show');
        $router->post('/', 'v1\ProductController@store');
    });

    /**
     * Wishlist routes
     */
    $router->group([CONST_PREFIX => 'wishlist'], function () use ($router) {
        $router->get('/{user_id}', 'v1\WishlistController@index');
        $router->get('/detail/{id}', 'v1\WishlistController@show');
        $router->get('/delete/{id}', 'v1\WishlistController@delete');
    });

    /**
     * Store routes
     */
    $router->group([CONST_PREFIX => 'store'], function () use ($router) {
        $router->get('/', 'v1\StoreController@index');
        $router->get('/{id}', 'v1\StoreController@show');
        $router->post('/', 'v1\StoreController@store');
        $router->post('/update/{id}', 'v1\StoreController@update');
        $router->get('/delete/{id}', 'v1\StoreController@delete');
    });
});