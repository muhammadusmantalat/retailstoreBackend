<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreManagerIdToShortCaseReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('short_case_reasons', function (Blueprint $table) {
            $table->unsignedBigInteger('store_manager_id')->nullable();
            $table->foreign('store_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('short_case_reasons', function (Blueprint $table) {
            $table->dropColumn('store_manager_id');
            $table->dropColumn('store_id');
        });
    }
}
