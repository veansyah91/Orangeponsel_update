<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPurchaseReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_return_details', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_return_id')->nullable();
            $table->foreign('purchase_return_id')->references('id')->on('purchase_returns')->onUpdate('cascade')->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_return_details', function (Blueprint $table) {
            //
        });
    }
}
