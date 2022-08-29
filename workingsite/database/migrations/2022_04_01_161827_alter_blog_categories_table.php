<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBlogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropColumn(array('parent_id', 'order'));
            $table->renameColumn('name', 'title');
            $table->text('description', 255);
            $table->text('image', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->integer('parent_id');
            $table->integer('order');
            $table->renameColumn('title', 'name');
            $table->dropColumn(array('description', 'image'));
        });
    }
}
