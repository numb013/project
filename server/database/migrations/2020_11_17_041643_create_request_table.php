<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RequestListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('viewer_id');
            $table->integer('cast_id');
            $table->tinyInteger('status');
            $table->tinyInteger('category');
            $table->string('to_name');
            $table->string('message');
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
        Schema::dropIfExists('request_list');
    }
}
