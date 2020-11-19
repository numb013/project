<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBankAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bank_account', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cast_id');
            $table->integer('company_id');
            $table->string('bank_code')->comment('銀行コード');
            $table->string('bank_branch_code');
            $table->string('account_no');
            $table->string('account_name');
            $table->integer('accouont_type')->comment('0:普通、1:当座');
            $table->string('transfer_name')->comment('振り込み名義');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_bank_account');
    }
}
