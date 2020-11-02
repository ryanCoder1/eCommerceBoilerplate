<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('show_on_website')->default(0);
            $table->string('title', 40)->nullable();
            $table->string('caption', 125)->nullable();
            $table->string('image_name')->nullable();
            $table->string('image_mime')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_size')->nullable();
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
        Schema::dropIfExists('banner');
    }
}
