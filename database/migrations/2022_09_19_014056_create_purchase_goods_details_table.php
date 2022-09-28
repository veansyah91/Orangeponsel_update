<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseGoodsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_goods_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_goods_id');
            $table->foreign('purchase_goods_id')->references('id')->on('purchase_goods')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->string('product_name');
            $table->bigInteger('value'); //total nilai belanja
            $table->integer('qty');
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
        Schema::dropIfExists('purchase_goods_details');
    }
}
