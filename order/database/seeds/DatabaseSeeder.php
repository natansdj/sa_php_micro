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

        \App\Models\Invoice::truncate();
        \App\Models\Cart::truncate();

        factory(\App\Models\Cart::class, 6)->create();

        factory(\App\Models\Cart::class, 1)->create(['status' => 'pending', 'qty' => 2, 'invoice_id' => 1])->each(
            function($cart) {
                factory(\App\Models\Invoice::class, 1)->create([
                    'total' => ($cart->price * $cart->qty)
                ]);
            }
        );

        // Enable it back
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
