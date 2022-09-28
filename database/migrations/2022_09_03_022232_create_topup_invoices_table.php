<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopupInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topup_invoices', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('outlet_id');
            $table->unsignedBigInteger('customer_id');
            $table->timestamps();
            $table->string('product');
            $table->string('address_no');
            $table->bigInteger('selling_price');
            $table->bigInteger('unit_cost');
            $table->string('server');
            $table->string('invoice_number');

            $table->foreign('outlet_id')->references('id')->on('outlets')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topup_invoices');
    }
}
