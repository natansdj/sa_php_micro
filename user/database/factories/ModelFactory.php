<?php

$factory->define(\App\Models\User::class, function (Faker\Generator $faker) {
	$hasher = app()->make('hash');

	return [
		'email'    => $faker->email,
		'name'     => $faker->name,
		'password' => $hasher->make("root"),
		'surname'  => $faker->name
	];
});
