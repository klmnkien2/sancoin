<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id', false, true)->unique();
            $table->integer('from_id', false, true);
            $table->string('from_account', 255)->nullable();
            $table->integer('to_id', false, true);
            $table->string('to_account', 255)->nullable();
            $table->string('amount', 100);
            $table->char('type', 10)->default('order');
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
        Schema::dropIfExists('transactions');
    }
}
