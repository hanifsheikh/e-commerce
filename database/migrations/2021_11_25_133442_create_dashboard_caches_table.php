<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDashboardCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_caches', function (Blueprint $table) {
            $table->id();
            $table->unsignedFloat('sum_of_commission');
            $table->unsignedFloat('sum_of_commission_previous_month');
            $table->unsignedInteger('total_orders_in_current_month');
            $table->unsignedInteger('total_orders_in_previous_month');
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
        Schema::dropIfExists('dashboard_caches');
    }
}
