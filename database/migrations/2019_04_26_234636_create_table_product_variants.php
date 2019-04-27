<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id');
            $table->integer('product_id');
            $table->string('shopify_product_id');
            $table->string('shopify_variant_id');
            $table->json('metadata')->nullable();
            $table->string('default_image_url')->nullable();
            $table->double('price', 10, 2);
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
        Schema::drop('product_variants');
    }
}
