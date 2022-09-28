<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_goods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outlet_id');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('cashier_id')->null();
            $table->foreign('cashier_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->string('supplier_name');
            $table->string('invoice_number');
            $table->date('date');
            $table->bigInteger('value');
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
        Schema::dropIfExists('purchase_goods');
    }
}
