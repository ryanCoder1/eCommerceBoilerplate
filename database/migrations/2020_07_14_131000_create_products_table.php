<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->nullable();
            $table->string('product_code')->nullable();
            $table->string('title')->nullable();
            $table->string('description', 500)->nullable();
            $table->string('details')->nullable();
            $table->float('price', 10,2)->nullable();
            $table->float('sale_price', 10,2)->nullable();
            $table->integer('in_stock')->nullable();
            $table->string('color')->nullable();
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
        Schema::dropIfExists('products');
    }
}
