<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCartsAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('draft_order_id')->nullable();
            $table->enum('status', ['open', 'draft', 'cancelled', 'complete'])->default('open');
            $table->timestamp('drafted_at', '')->nullable();
            $table->timestamp('completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('draft_order_id');
            $table->dropColumn('status');
            $table->dropColumn('drafted_at');
            $table->dropColumn('completed_at');
        });
    }
}
