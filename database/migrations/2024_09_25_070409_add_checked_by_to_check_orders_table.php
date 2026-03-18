<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckedByToCheckOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('check_orders', function (Blueprint $table) {
            $table->string('checked_by')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('check_number')->nullable();
            $table->string('invoice_amount')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('check_orders', function (Blueprint $table) {
            $table->dropColumn('checked_by');
            $table->dropColumn('payment_method');
            $table->dropColumn('check_number');
            $table->dropColumn('invoice_amount');
        });
    }
}
