<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditPartnerInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_partner_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credit_partner_id');
            $table->integer('nomor');
            $table->timestamps();

            $table->foreign('credit_partner_id')->references('id')->on('credit_partners')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_claim_details');
    }
}
