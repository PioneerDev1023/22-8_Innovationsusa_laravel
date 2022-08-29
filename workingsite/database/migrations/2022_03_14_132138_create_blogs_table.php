<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->text('title', 150);
            $table->text('slug', 165);
            $table->text('excerpt', 165);
            $table->text('sub_title', 150);
            $table->text('description', 420);
            $table->text('meta_kwds', 240);
            $table->text('header_img', 120);
            $table->text('header_caption', 240)->nullable();
            $table->text('body_img1', 120);
            $table->text('body_caption1', 240)->nullable();
            $table->text('body_img2', 120);
            $table->text('body_caption2', 240)->nullable();
            $table->text('footer_img', 120)->nullable();
            $table->text('footer_caption', 240)->nullable();
            $table->text('location', 240)->nullable();
            $table->longText('body');
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
        Schema::dropIfExists('blogs');
    }
}
