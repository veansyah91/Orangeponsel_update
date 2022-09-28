<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_return_id');
            $table->timestamps();

            $table->foreign('sales_return_id')->references('id')->on('sales_returns')->onUpdate('cascade')->onDelete('cascade');
            $table->string('product_name');
            $table->integer('qty')->default(0);
            $table->bigInteger('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_return_details');
    }
}
