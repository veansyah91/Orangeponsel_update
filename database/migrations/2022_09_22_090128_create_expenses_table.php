<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outlet_id');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('cash_id')->nullable();
            $table->foreign('cash_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->string('cash_name')->nullable();
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->string('item_name');
            $table->bigInteger('value');
            $table->date('date');
            $table->string('description')->nullable();
            $table->string('no_ref');
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
        Schema::dropIfExists('expenses');
    }
}
