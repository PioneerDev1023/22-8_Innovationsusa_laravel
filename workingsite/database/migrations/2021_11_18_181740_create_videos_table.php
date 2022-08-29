<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
          $table->increments('id');
          $table->string('title', 120);
          $table->string('embed_code', 900);
          $table->string('meta_desc', 360)->nullable();
          $table->string('meta_keywords', 180)->nullable();
          $table->string('slug', 180);
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
        Schema::dropIfExists('videos');
    }
}
