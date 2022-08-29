<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_storage', function (Blueprint $table) {
            $table->increments('id')->nullable(false)->unsigned();
            $table->integer("form_id");
            $table->longText('data');
            $table->timestamps();
            $table->tinyInteger('status')->default(1); // 0: deprecated 1: normal
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formdata');
    }
}
