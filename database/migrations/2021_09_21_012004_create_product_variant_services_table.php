<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variant_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_variant_id');
            $table->unsignedInteger('delivery_charge')->default(0);
            $table->unsignedInteger('delivery_charge_outside')->nullable();
            $table->boolean('payment_first')->default(0);
            $table->unsignedInteger('payment_first_amount_in_percentage')->nullable();
            $table->unsignedInteger('payment_first_amount_in_taka')->nullable();
            $table->boolean('payment_first_delivery_charge')->default(0);
            $table->unsignedInteger('replacement_in_days')->nullable();
            $table->unsignedInteger('free_delivery_upto')->nullable();
            $table->string('delivery_area')->nullable();
            $table->unsignedInteger('gurantee_in_months')->nullable();
            $table->unsignedInteger('warranty_in_months')->nullable();
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
        Schema::dropIfExists('product_variant_services');
    }
}
