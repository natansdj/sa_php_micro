<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderCreateInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->increments('id');
            
            $table->double('total');
            $table->integer('user_id');
            $table->text('address');
            $table->string('status');
            $table->string('method');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))
                ->attributes(DB::raw('on update CURRENT_TIMESTAMP'))
                ->extra(DB::raw('ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoice');
    }
}
