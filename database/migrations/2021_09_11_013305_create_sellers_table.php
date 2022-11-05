<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('active')->default(0);
            $table->boolean('is_product_banned')->default(0);
            $table->boolean('is_feature_banned')->default(0);
            $table->unsignedFloat('commission_rate', 3, 2)->default(1.00);
            $table->string('email')->unique();
            $table->string('avatar')->default('avatar_default.jpg');
            $table->unsignedInteger('theme')->default(0);
            $table->text('owner_address');
            $table->text('selling_products');
            $table->text('company_address')->nullable();
            $table->string('logo')->default('no_image.png');
            $table->string('banner')->default('banner_default.jpg');
            $table->string('url')->nullable();
            $table->string('contact_no')->unique();
            $table->string('company_name')->unique();
            $table->string('shop_slug')->unique();
            $table->string('alternative_contact_no')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('documents_submitted_at')->nullable();
            $table->timestamp('documents_approved_at')->nullable();
            $table->timestamp('documents_declined_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('sellers');
    }
}
