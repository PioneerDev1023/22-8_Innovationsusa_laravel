<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogs', function (Blueprint $table) {
            $table->boolean('status')->default(1);
        });
        Schema::table('catalogs', function (Blueprint $table) {
            $table->boolean('status')->default(0)->change();
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->boolean('status')->default(1);
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->boolean('status')->default(0)->change();
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->boolean('status')->default(1);
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->boolean('status')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalogs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
