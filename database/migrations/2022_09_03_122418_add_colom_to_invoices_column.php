<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColomToInvoicesColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('account_name');
            $table->unsignedBigInteger('cashier_id')->nullable(); //account utk kasir

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
}
