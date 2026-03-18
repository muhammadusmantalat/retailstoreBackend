<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignVendorIdToAssignVendorToDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assign_vendor_to_departments', function (Blueprint $table) {
            $table->unsignedBigInteger('assignVendor_id')->nullable();
            $table->foreign('assignVendor_id')->references('id')->on('assign_vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assign_vendor_to_departments', function (Blueprint $table) {
            $table->dropColumn('assignVendor_id');
        });
    }
}
