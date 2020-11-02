<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDefaultImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_default_image', function (Blueprint $table) {
            $table->increments('id');
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
        Schema::dropIfExists('product_default_image');
    }
}
