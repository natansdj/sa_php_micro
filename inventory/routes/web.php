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

$router->get('/', function () use ($router) {
    return $router->app->version() . 'inventory app';
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    /**
     * Category routes
     */
    $router->group(['prefix' => 'category'], function () use ($router) {
        $router->get('/', 'v1\CategoryController@index');
        $router->get('/{id}', 'v1\CategoryController@show');
    });

    /**
     * Product routes
     */
    $router->group(['prefix' => 'product'], function () use ($router) {
        $router->get('/', 'v1\ProductController@index');
        $router->get('/{id}', 'v1\ProductController@show');
    });
});