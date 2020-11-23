<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('category')->nullable();
            $table->tinyInteger('can_type')->nullable()->comment("出来る事");
            $table->integer('price')->comment('料金');
            $table->integer('period')->comment('期間');
            $table->string('descript')->comment("説明");
            $table->integer('total_post')->defalt(0)->comment("動画数");
            $table->integer('get_coin')->defalt(0)->comment("獲得コイン");
            $table->double('score')->defalt(0)->comment("評価");
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
        Schema::dropIfExists('casts');
    }
}
