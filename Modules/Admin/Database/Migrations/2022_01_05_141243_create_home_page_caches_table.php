<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomePageCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_page_caches', function (Blueprint $table) {
            $table->id();
            $table->json("recommended_products")->nullable();
            $table->json("category_caches")->nullable();
            $table->json("category_products")->nullable();
            $table->json("daily_deals")->nullable();
            $table->json("best_selling")->nullable();
            $table->json("collections")->nullable();
            $table->json("brands")->nullable();
            $table->json("sellers")->nullable();
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
        Schema::dropIfExists('home_page_caches');
    }
}
