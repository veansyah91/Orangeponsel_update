<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credit_application_id');
            $table->bigInteger('jumlah');
            $table->integer('bayar_ke');
            $table->integer('nomor_pembayaran');
            $table->timestamps();

            $table->foreign('credit_application_id')->references('id')->on('credit_applications')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_payments');
    }
}
