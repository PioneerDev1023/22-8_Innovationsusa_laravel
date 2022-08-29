<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPressReleasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('press_releases', function (Blueprint $table) {

            $table->text('sub_title', 150);
            $table->text('location', 240)->nullable();
            $table->renameColumn('img_url', 'header_img');
            $table->text('footer_img', 120)->nullable();
            $table->text('header_caption', 240)->nullable();
            $table->text('footer_caption', 240)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('press_releases', function (Blueprint $table) {

            $table->dropColumn('sub_title');
            $table->dropColumn('location');
            $table->renameColumn('header_img', 'img_url');
            $table->dropColumn('footer_img');
            $table->dropColumn('header_caption');
            $table->dropColumn('footer_caption');

        });
    }
}
