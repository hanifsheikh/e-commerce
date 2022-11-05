<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_sellers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('seller_id');
            $table->string('seller_name');
            $table->string('seller_company_name');
            $table->string('seller_contact_no');
            $table->string('seller_logo')->nullable();
            $table->unsignedInteger('seller_order_amount');
            $table->unsignedInteger('seller_order_delivery_charge');
            // $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            // $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            // Postgres fix 
            $table->timestamp('created_at')->default(now());
            $table->timestamp('updated_at')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_sellers');
    }
}
