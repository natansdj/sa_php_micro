<?php

return [
	'default' => env('CACHE_DRIVER', 'redis'),

	'stores' => [
		'file'  => [
			'driver' => 'file',
			'path'   => storage_path('framework/cache'),
		],
		'redis' => [
			'driver'     => 'redis',
			'connection' => 'default',
		],
	],

	'prefix' => env(
		'CACHE_PREFIX',
		str_slug(env('APP_NAME', 'laravel'), '_') . '_cache'
	),
];