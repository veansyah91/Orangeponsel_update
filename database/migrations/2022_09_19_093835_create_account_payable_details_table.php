<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountPayableDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_payable_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_payable_id')->unsigned();
            $table->foreign('account_payable_id')->references('id')->on('account_payables')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('debit')->default(0);
            $table->bigInteger('credit')->default(0);
            $table->string('ref')->nullable();
            $table->string('description')->nullable();
            $table->date('date');
            $table->date('due_date')->null();
            $table->boolean('is_paid')->default(false);
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
        Schema::dropIfExists('account_payable_details');
    }
}
