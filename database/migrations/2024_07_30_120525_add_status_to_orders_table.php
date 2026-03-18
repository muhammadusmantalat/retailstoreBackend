<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('in-progress');
            $table->string('invoice_number')->nullable();
            $table->string('store_manager_name')->nullable();
            $table->string('store_name')->nullable();
            $table->string('vendor_name')->nullable();
            $table->date('date')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('invoice_number');
            $table->dropColumn('store_manager_name');
            $table->dropColumn('store_name');
            $table->dropColumn('vendor_name');
            $table->dropColumn('date');
        });
    }
}
