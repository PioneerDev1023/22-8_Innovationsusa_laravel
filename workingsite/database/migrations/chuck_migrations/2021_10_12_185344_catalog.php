<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Catalog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('catalogs', function (Blueprint $table) {
        $table->increments('id');
        $table->string('title', 120);
        $table->string('embed_code', 900);
        $table->string('meta_desc', 360)->nullable();
        $table->string('thumb_url', 270)->nullable();
        $table->dateTime('pub_date', $precision = 0);
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
        Schema::dropIfExists('catalogs');
    }
}
