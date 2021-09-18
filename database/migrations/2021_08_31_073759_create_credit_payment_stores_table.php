<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditPaymentStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_payment_stores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credit_sale_id');
            $table->unsignedBigInteger('credit_payment_id');
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('credit_sale_id')->references('id')->on('credit_sales')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('credit_payment_id')->references('id')->on('credit_payments')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_payment_stores');
    }
}
