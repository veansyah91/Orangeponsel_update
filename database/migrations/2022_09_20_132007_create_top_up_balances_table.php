<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopUpBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('top_up_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outlet_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('server_id');
            $table->unsignedBigInteger('cashier_id')->nullable();
            $table->string('supplier_name');
            $table->string('server_name');
            $table->string('cashier_name')->nullable();
            
            $table->foreign('outlet_id')->references('id')->on('outlets')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('server_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('cashier_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');

            $table->string('invoice_number');
            $table->bigInteger('value');
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
        Schema::dropIfExists('top_up_balances');
    }
}
