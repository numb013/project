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
            $table->string('bank_code');
            $table->string('bank_branch_code');
            $table->string('account_no');
            $table->string('account_name');
            $table->integer('accouont_type');
            $table->string('transfer_name')->nullable();
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
