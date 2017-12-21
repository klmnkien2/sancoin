<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->nullable();
            $table->string('fullname', 255)->nullable();
            $table->string('id_number', 30)->nullable();
            $table->dateTime('id_created_at')->nullable();
            $table->string('id_created_by', 255)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('image1', 500)->nullable();
            $table->string('image2', 500)->nullable();
            $table->string('image3', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
