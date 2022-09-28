<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_returns', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_returs', function (Blueprint $table) {
            //
        });
    }
}
