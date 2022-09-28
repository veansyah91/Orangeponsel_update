<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountPayablePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_payable_payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('outlet_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('supplier_name');
            
            $table->foreign('outlet_id')->references('id')->on('outlets')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('cascade');

            $table->string('invoice_number');
            $table->bigInteger('value');
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_payable_payments');
    }
}
