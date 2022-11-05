<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_variant_id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('seller_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('customer_id');
            $table->boolean('item_received')->default(false);
            $table->boolean('item_returned')->default(false);
            $table->string('brand_name');

            // Meta 
            $table->text('about_the_item');
            $table->longText('product_description')->nullable();
            $table->text('product_variant_embed_video_url')->nullable();
            $table->text('product_components')->nullable();
            $table->text('product_components_ratio_per_gram')->nullable();
            // Meta

            // Services 
            $table->string('delivery_area');
            $table->boolean('payment_first')->default(0);
            $table->unsignedInteger('payment_first_amount_in_percentage')->nullable();
            $table->unsignedInteger('payment_first_amount_in_taka')->nullable();
            $table->boolean('payment_first_delivery_charge')->default(0);
            $table->unsignedInteger('free_delivery_upto')->nullable();
            $table->unsignedInteger('replacement_in_days')->nullable();
            $table->unsignedInteger('gurantee_in_months')->nullable();
            $table->unsignedInteger('warranty_in_months')->nullable();
            $table->unsignedInteger('delivery_charge');
            $table->unsignedInteger('delivery_charge_outside')->nullable();
            // Services 

            $table->string('product_title');
            $table->string('product_variant_title')->nullable();
            $table->string('sku');
            $table->string('shape')->nullable();
            $table->string('unit')->nullable();
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
            $table->unsignedInteger('stock_quantity');
            $table->unsignedInteger('regular_price');
            $table->unsignedInteger('offer_price')->default(0);
            $table->unsignedInteger('price');
            $table->unsignedInteger('total_price');
            $table->unsignedFloat('commission_rate', 8, 2)->nullable();
            $table->unsignedFloat('commission', 8, 2)->nullable();
            $table->unsignedInteger('quantity');
            $table->smallInteger('discount_in_percentage')->default(0);
            $table->boolean('cash_on_delivery')->nullable();
            $table->timestamp('delivery_time');
            $table->string('product_variant_url');
            $table->string('image');
            $table->json('images');
            $table->string('status');
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
        Schema::dropIfExists('order_products');
    }
}
