<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductStylesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_styles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->integer('in_stock')->nullable();
            $table->float('price', 10, 2)->nullable();
            $table->float('sale_price', 10, 2)->nullable();
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
        Schema::dropIfExists('product_styles');
    }
}
