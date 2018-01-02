<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true);
            $table->integer('partner_id', false, true)->nullable();
            $table->char('order_type', 10);
            $table->char('coin_type', 10);
            $table->string('coin_amount', 100);
            $table->string('hash', 100)->nullable();
            $table->char('hash_status', 10)->nullable();
            $table->string('amount', 100);
            $table->integer('transaction_id', false, true)->nullable();
            $table->char('transaction_status', 10)->nullable();
            $table->char('status', 10);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
