<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImmediateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('immediate_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('total_cases')->nullable();
            $table->integer('received_cases')->nullable();
            $table->integer('remaining_cases')->nullable();
            $table->integer('trip_cases_1')->nullable();
            $table->integer('trip_cases_2')->nullable();
            $table->integer('trip_cases_3')->nullable();
            $table->integer('trip_cases_4')->nullable();
            $table->integer('trip_cases_5')->nullable();
            $table->integer('trip_cases_6')->nullable();
            $table->integer('trip_cases_7')->nullable();
            $table->integer('trip_cases_8')->nullable();
            $table->integer('trip_cases_9')->nullable();
            $table->integer('trip_cases_10')->nullable();
            $table->string('short_cases_status')->nullable();
            $table->string('short_case_reason')->nullable();
            $table->string('vendor_recepit')->nullable();
            $table->string('checked_by')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('check_number')->nullable();
            $table->string('invoice_amount')->nullable();
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
        Schema::dropIfExists('immediate_orders');
    }
}
