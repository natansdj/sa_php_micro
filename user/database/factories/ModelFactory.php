<?php

$factory->define(\App\Models\User::class, function (Faker\Generator $faker) {

    return [
        'email'    => $faker->safeEmail,
        'password' => '123456', //automatically hashed from User->setPasswordAttribute
        'username' => $faker->userName,
        'name'     => $faker->name,
        'address'  => $faker->address,
        'phone'    => $faker->phoneNumber,
    ];
});

$factory->defineAs(App\Models\User::class, 'admin', function (Faker\Generator $faker) use ($factory) {
    $data = $factory->raw('App\Models\User');

    return array_merge($data, [
        'name'  => 'Admin ' . $faker->firstName(),
        'email' => 'admin@example.org',
    ]);
});
