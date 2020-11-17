<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('name');
            $table->integer('category');
            $table->string('address');
            $table->integer('tel');
            $table->string('email');
            $table->string('hp_url');
            $table->string('contact_name');
            $table->string('contact_tel');
            $table->string('contact_mail');
            $table->string('company_address');
            $table->string('company_tel');
            $table->integer('accouont_type');
            $table->string('transfer_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company');
    }
}
