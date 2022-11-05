<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('seller_id');
            $table->unsignedInteger('offer_id')->nullable();
            $table->string('product_title');
            $table->string('product_variant_title')->nullable();
            $table->string('sku')->unique();
            $table->string('shape')->nullable();
            $table->string('item_diameter')->nullable();
            $table->string('weight')->nullable();
            $table->string('authenticity')->nullable();
            $table->string('color')->nullable();
            $table->string('color_code')->nullable();
            $table->string('texture')->nullable();
            $table->string('model_no')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->string('size')->nullable();
            $table->string('material')->nullable();
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->unsignedInteger('regular_price')->default(0);
            $table->unsignedInteger('offer_price')->default(0);
            $table->smallInteger('discount_in_percentage')->default(0);
            $table->boolean('cash_on_delivery')->nullable();
            $table->unsignedInteger('delivery_time')->default(1);
            $table->unsignedInteger('total_sales')->default(0);
            $table->string('product_variant_url');
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
        Schema::dropIfExists('product_variants');
    }
}
