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
	return $router->app->version() . 'user app';
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
	/**
	 * Authentication route
	 */
	$router->group(['prefix' => '/auth'], function () use ($router) {
		$router->post('login', 'v1\AuthController@authenticate');
		$router->post('register', 'v1\AuthController@register');
		$router->get('authenticated', 'v1\AuthController@getAuthenticatedUser');
		$router->get('invalidate', 'v1\AuthController@invalidate');
		$router->get('refresh', 'v1\AuthController@refresh');
	});

	/**
	 * Users routes with jwt middleware
	 */
	$router->group(['middleware' => 'api.jwt', 'prefix' => 'users'], function () use ($router) {
		$router->get('/', 'v1\UserController@index');
		$router->get('/{id}', 'v1\UserController@show');

		$router->put('/{id}', 'v1\UserController@update');
		$router->put('/{id}/password', 'v1\UserController@updatePassword');
		$router->delete('/{id}', 'v1\UserController@delete');
	});
});