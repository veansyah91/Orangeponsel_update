<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditStatusColomnCreditInvoiceApplicationInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_application_invoices', function(Blueprint $table)
        {
            $table->dropColumn('status');
        });

        Schema::table('credit_application_invoices', function(Blueprint $table)
        {
            $table->enum('status', ['waiting', 'paid','claiming']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_application_invoices', function (Blueprint $table) {
            //
        });
    }
}
