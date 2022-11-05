<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerDataCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_data_caches', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('seller_id');
            $table->unsignedFloat('current_commission_rate')->default(1.5);
            $table->unsignedInteger('total_sale_amount')->default(0);
            $table->unsignedInteger('total_sale_amount_in_current_month')->default(0);
            $table->unsignedInteger('total_sale_amount_in_previous_month')->default(0);
            $table->unsignedInteger('total_commission_in_current_month')->default(0);
            $table->integer('due')->default(0);
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
        Schema::dropIfExists('seller_data_caches');
    }
}
