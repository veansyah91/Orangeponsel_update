<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditPaymentOldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_payment_olds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credit_app_old_id');
            $table->integer('nomor_nota')->nullable();
            $table->bigInteger('jumlah'); 
            $table->integer('angsuran_ke'); 
            $table->string('pencatat'); 
            $table->string('kolektor')->nullable(); 
            $table->string('outlet')->nullable(); 
            $table->enum('status',[0,1]); 
            $table->timestamps();

            $table->foreign('credit_app_old_id')->references('id')->on('credit_application_olds')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_payment_olds');
    }
}
