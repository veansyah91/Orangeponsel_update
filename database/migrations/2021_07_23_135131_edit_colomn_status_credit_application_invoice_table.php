<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditColomnStatusCreditApplicationInvoiceTable extends Migration
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
            $table->enum('status', ['waiting', 'paid']);
            $table->string('kode');
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
