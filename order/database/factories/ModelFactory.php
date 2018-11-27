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
    $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

    $user_ids = \DB::table('users')->select('id')->get();
    $user_id  = $faker->randomElement($user_ids)->id;

    return [
        'total' => $faker->randomNumber(5),
        'user_id' => $faker->user_id,
        'address' => $faker->realText(50),
        'status' => $faker->realText(15),
        'method' => $faker->realText(15),
    ];
});

//Cart
$factory->define(App\Models\Cart::class, function (Faker\Generator $faker) {
    $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

    $user_ids = \DB::table('users')->select('id')->get();
    $user_id  = $faker->randomElement($user_ids)->id;

    $invoice_ids = \DB::table('invoice')->select('id')->get();
    $invoice_id  = $faker->randomElement($invoice_ids)->id;

    $product_ids = \DB::table('product')->select('id')->get();
    $product_id  = $faker->randomElement($product_ids)->id;

    return [
        'total' =>  $faker->randomNumber(5),
        'status' => $faker->realText(15),
        'product_id' => $faker->product_id,
        'user_id' => $faker->user_id,
        'stock' => $faker->numberBetween(10, 20),
        'invoice_id' => $faker->invoice_id,
    ];
});
