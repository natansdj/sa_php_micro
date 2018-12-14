<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

//Invoice
$factory->define(App\Models\Invoice::class, function (Faker\Generator $faker) {
    $user_ids = \DB::table('users')->select('id')->get();
    $user_id  = $faker->randomElement($user_ids)->id;

    $user = \DB::table('users')->select('address')->find($user_id);

    return [
        'user_id' => $user_id,
        'address' => $user->address,
        'method' => $faker->randomElement(array('atm transfer', 'internet banking', 'credit')),
    ];
});

//Cart
$factory->define(App\Models\Cart::class, function (Faker\Generator $faker) {
    $product_ids = \DB::table('product')->select('id')->get();
    $product_id  = $faker->randomElement($product_ids)->id;

    $product = \DB::table('product')->select('harga')->find($product_id);

    return [
        'price' =>  $product->harga,
        'product_id' => $product_id,
        'user_id' => $faker->numberBetween(2, 6),
        'qty' => $faker->numberBetween(1, 9)
    ];
});
