<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCastAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cast_admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('company_id');
            $table->tinyInteger('authority')->defalt(0)->comment("0:配信者 1:管理者");
            $table->string('category')->nullable();
            $table->tinyInteger('can_type')->nullable()->comment("出来る事");
            $table->integer('price')->comment('料金');
            $table->integer('period')->comment('期間');
            $table->string('descript')->comment("説明");
            $table->integer('total_post')->defalt(0)->comment("動画数");
            $table->integer('get_coin')->defalt(0)->comment("獲得コイン");
            $table->double('score')->defalt(0)->comment("評価");
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('cast_admins');
    }
}
