<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColomnToCreditPaymentOldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_payment_olds', function (Blueprint $table) {
            $table->string('terlambat');
            $table->string('tanggal_bayar');
            $table->string('jatuh_tempo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_payment_olds', function (Blueprint $table) {
            //
        });
    }
}
