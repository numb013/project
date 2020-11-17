<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestMovieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_movie', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_id');
            $table->integer('hash_id');
            $table->tinyInteger('status');
            $table->tinyInteger('check_status');
            $table->string('check_memo');
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
        Schema::dropIfExists('request_movie');
    }
}
