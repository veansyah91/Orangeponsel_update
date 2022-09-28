<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountReceivableDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_receivable_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_receivable_id')->unsigned();
            $table->foreign('account_receivable_id')->references('id')->on('account_receivables')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('debit')->default(0);
            $table->bigInteger('credit')->default(0);
            $table->string('ref')->nullable();
            $table->string('description')->nullable();
            $table->date('date');
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
        Schema::dropIfExists('account_receivable_details');
    }
}
