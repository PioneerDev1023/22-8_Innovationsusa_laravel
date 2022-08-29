<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontCarouselsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('front_carousels', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('slide_number');
            $table->text('slide_image', 140);
            $table->text('button_text', 140);
            $table->text('button_link', 280);
            $table->text('caption_text_top', 140);
            $table->text('caption_text_bottom', 140);
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('front_carousels');
    }
}
