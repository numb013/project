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
            $table->string('hp_url')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_tel')->nullable();
            $table->string('contact_mail')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_tel')->nullable();
            $table->integer('accouont_type')->nullable();
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
        Schema::dropIfExists('company');
    }
}
