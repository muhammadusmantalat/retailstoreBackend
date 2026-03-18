<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('total_cases')->nullable();
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
            $table->string('image')->nullable();
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
        Schema::dropIfExists('check_orders');
    }
}
