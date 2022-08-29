<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('wd_id')->unique();
            $table->string('company_name')->nullable();
            $table->string('street_address', 140)->nullable();
            $table->string('city', 30)->nullable();
            $table->string('state', 24)->nullable();
            $table->string('country', 30)->nullable();
            $table->string('zip_code', 11)->nullable();
            $table->string('trade_cert')->nullable();
            $table->string('website')->nullable();
            $table->string('comments', 300)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
