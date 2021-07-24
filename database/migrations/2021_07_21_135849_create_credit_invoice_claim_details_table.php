<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditInvoiceClaimDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_invoice_claim_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credit_partner_invoice_id');
            // credit application invoice
            $table->unsignedBigInteger('credit_app_inv_id');
            $table->timestamps();

            $table->foreign('credit_partner_invoice_id')->references('id')->on('credit_partner_invoices')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('credit_app_inv_id')->references('id')->on('credit_application_invoices')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_invoice_claim_details');
    }
}
