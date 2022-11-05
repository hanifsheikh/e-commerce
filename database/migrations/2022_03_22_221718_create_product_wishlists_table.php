<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_variant_id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('seller_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('customer_id');
            $table->string('brand_name');
            $table->string('seller_company');
            $table->string('product_title');
            $table->string('product_variant_title')->nullable();
            $table->string('sku');
            $table->string('product_variant_url');
            $table->string('color')->nullable();
            $table->string('color_code')->nullable();
            $table->string('texture')->nullable();
            $table->string('size')->nullable();
            $table->string('material')->nullable();
            $table->unsignedInteger('regular_price');
            $table->unsignedInteger('offer_price')->default(0);
            $table->unsignedInteger('price');
            $table->unsignedInteger('quantity')->default(1);
            $table->string('image');
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
        Schema::dropIfExists('product_wishlists');
    }
};
