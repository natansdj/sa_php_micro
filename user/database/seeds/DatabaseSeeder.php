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

        \App\Models\User::truncate();

        factory(\App\Models\User::class, 'admin')->create();
        factory(\App\Models\User::class, 5)->create();

        // Enable it back
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}