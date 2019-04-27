<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->double('sub_total', 10, 2);
            $table->double('tax_total', 10, 2);
            $table->double('total', 10, 2);
            $table->string('payment_method_id')->nullable();
            $table->string('payment_method_last_4')->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('charge_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('receipt_url')->nullable();
            $table->json('metadata')->nullable();
            $table->integer('cart_id')->nullable();
            $table->integer('order_id')->nullable();
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
        Schema::drop('payments');
    }
}
