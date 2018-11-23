<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderCreateCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))
                ->attributes(DB::raw('on update CURRENT_TIMESTAMP'))
                ->extra(DB::raw('ON UPDATE CURRENT_TIMESTAMP'));
            $table->double('total');
            $table->string('status');
            $table->string('product_id');
            $table->integer('user_id');
            $table->integer('stock');
            $table->integer('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cart');
    }
}
