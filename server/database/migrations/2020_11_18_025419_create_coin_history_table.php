<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('user_bank_id');
            $table->integer('coin');
            $table->integer('type');
            $table->integer('used_point');
            $table->string('withdraw_year_month');
            $table->integer('withdraw_state');
            $table->integer('drawn');
            $table->string('comment')->unique();
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
        Schema::dropIfExists('admins');
    }
}
