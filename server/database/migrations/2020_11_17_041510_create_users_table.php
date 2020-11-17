<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->comment('0:視聴者、1:キャスト');
            $table->string('hash_id', 10)->nullable();
            $table->tinyInteger('state')->nullable()->comment('0:仮登録、1:登録済、-1:退会、-2:管理者より利用停止');
            $table->string('login_id')->nullable()->unique();
            $table->string('password', 64)->nullable();
            $table->string('sns_id', 64)->nullable();
            $table->tinyInteger('sns_type', 1)->nullable()->comment('1:apple 2:google、3:facebook');
            $table->string('access_token', 500)->nullable();
            $table->dateTime('last_login')->nullable();
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
        Schema::dropIfExists('users');
    }
}
