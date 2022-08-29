<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBooleanMentionsToSeenin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('SeenIn', function (Blueprint $table) {
            $table->boolean('include_mentions')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('SeenIn', function (Blueprint $table) {
            $table->dropColumn('include_mentions');
        });
    }
}
