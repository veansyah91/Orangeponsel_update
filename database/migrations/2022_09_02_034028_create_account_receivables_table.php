<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountReceivablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_receivables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('outlet_id')->unsigned();
            $table->foreign('outlet_id')->references('id')->on('outlets')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->string('customer_name');
            $table->bigInteger('balance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_receivables');
    }
}
