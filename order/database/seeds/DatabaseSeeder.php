<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checking because truncate() will fail
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        \App\Models\Promo::truncate();
        \App\Models\Invoice::truncate();
        \App\Models\Cart::truncate();

        $date = new DateTime();
        factory(\App\Models\Promo::class, 1)->create([
            'end_date' => $date->format('Y-m-d'),
        ]);

        factory(\App\Models\Cart::class, 6)->create();

        // without promo
        factory(\App\Models\Cart::class, 1)->create(['status' => 'pending', 'qty' => 2, 'invoice_id' => 1])->each(
            function($cart) {
                factory(\App\Models\Invoice::class, 1)->create([
                    'total' => ($cart->price * $cart->qty),
                    'promo_code' => NULL
                ]);
            }
        );

        // with promo
        $date = new DateTime('2019-12-31');
        factory(\App\Models\Promo::class, 1)->create(['end_date' => $date->format('Y-m-d')])->each(
            function ($promo) {
                factory(\App\Models\Cart::class, 1)->create(['status' => 'pending', 'qty' => 2, 'invoice_id' => 1])->each(
                    function ($cart) use ($promo) {
                        factory(\App\Models\Invoice::class, 1)->create([
                            'total' => ($cart->price * $cart->qty) - $promo->value,
                            'promo_code' => $promo->code
                        ]);
                    }
                );
            }
        );

        // Enable it back
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
