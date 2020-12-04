<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ManageRequestMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_request_message', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('request_list_id');
            $table->integer('admin_id')->nullable();
            $table->integer('cast_id')->nullable();
            $table->tinyInteger('confirmed');
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
        Schema::dropIfExists('manage_request_message');
    }
}
