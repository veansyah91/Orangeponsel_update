<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('outlet_id')->unsigned();
            $table->bigInteger('account_id')->unsigned();
            $table->string('account');
            $table->string('no_ref')->nullable();
            $table->bigInteger('debit')->default(0);
            $table->bigInteger('credit')->default(0);
            $table->timestamps();

            $table->foreign('outlet_id')->references('id')->on('outlets')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledgers');
    }
}
