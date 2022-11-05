<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('product_title');
            $table->string('unit')->default('pcs');
            $table->unsignedSmallInteger('category_id');
            $table->unsignedSmallInteger('category_second_level_id')->nullable();
            $table->unsignedSmallInteger('category_parent_id');
            $table->unsignedSmallInteger('brand_id');
            $table->unsignedInteger('offer_id')->nullable();
            $table->unsignedSmallInteger('seller_id');
            $table->float('ratings', 2, 1)->default(0);
            $table->unsignedInteger('total_sales')->default(0);
            $table->boolean('active')->default(1);
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
