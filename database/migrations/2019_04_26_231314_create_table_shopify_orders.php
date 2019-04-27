<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableShopifyOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id')->default(1);
            $table->string('shopify_id');
            $table->integer('cart_id')->nullable();
            $table->string('url');
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
        Schema::drop('shopify_orders');
    }
}
