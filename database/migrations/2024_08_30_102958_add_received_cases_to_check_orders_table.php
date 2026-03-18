<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceivedCasesToCheckOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('check_orders', function (Blueprint $table) {
            $table->integer('received_cases')->nullable();
            $table->integer('remaining_cases')->nullable();
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
            $table->dropColumn('received_cases');
            $table->dropColumn('remaining_cases');

        });
    }
}
