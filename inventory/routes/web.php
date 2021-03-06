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
    });

    /**
     * Product routes
     */
    $router->group([CONST_PREFIX => 'product'], function () use ($router) {
        $router->get('/', 'v1\ProductController@index');
        $router->get('/{id}', 'v1\ProductController@show');
    });
});