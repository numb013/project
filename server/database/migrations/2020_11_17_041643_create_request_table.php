<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('viewer_id');
            $table->integer('cast_id');
            $table->tinyInteger('status');
            $table->tinyInteger('category')->comment('1:祝い、1:応援、2:その他');
            $table->string('to_name')->comment('言って欲しい名前');
            $table->string('message')->comment('依頼内容');
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
        Schema::dropIfExists('request');
    }
}
