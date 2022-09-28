<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColomnToTopupInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topup_invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->nullable(); //account utk server
            $table->unsignedBigInteger('cashier_id')->nullable(); //account utk kasir

            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreign('cashier_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topup_invoices', function (Blueprint $table) {
            //
        });
    }
}
