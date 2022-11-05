<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerShippingAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->string('receiver_name');
            $table->text('receiver_address');
            $table->string('receiver_contact_no');
            $table->string('area');
            $table->string('district');
            $table->string('label')->default('home');
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
        Schema::dropIfExists('customer_shipping_addresses');
    }
}
