<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnToAccountReceivablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return voidhttps://github.com/veansyah91/Orangeponsel_update.git
     */
    public function up()
    {
        Schema::table('account_receivable', function (Blueprint $table) {
            $table->renameColumn('remaining', 'debit');
            $table->integer('credit')->default(0);
            $table->date('date')->nullable();
            
            $table->bigInteger('outlet_id')->unsigned()->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullabble();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_receivables', function (Blueprint $table) {
            //
        });
    }
}
