<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterOutletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inter_outlets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pihak_1');
            $table->unsignedBigInteger('pihak_2');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->integer('jumlah')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('konfirmasi')->nullable();
            $table->timestamps();

            $table->foreign('pihak_1')->references('id')->on('outlets')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pihak_2')->references('id')->on('outlets')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inter_outlets');
    }
}
