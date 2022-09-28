<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_journal', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('journal_id')->unsigned();
            $table->foreign('journal_id')->references('id')->on('journals')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('debit')->default(0);
            $table->bigInteger('credit')->default(0);

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
        Schema::dropIfExists('account_journals');
    }
}
