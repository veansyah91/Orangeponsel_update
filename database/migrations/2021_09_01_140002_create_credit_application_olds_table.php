<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditApplicationOldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_application_olds', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_akad')->nullable();
            $table->string('nama');
            $table->string('no_hp')->nullable();
            $table->string('tipe');
            $table->date('tanggal_akad');
            $table->date('tgl_terakhir_bayar');
            $table->bigInteger('dp');
            $table->bigInteger('angsuran');//angsuran perbulan
            $table->integer('tenor');//3 bulan, 10 bulan
            $table->bigInteger('total');//total hutang
            $table->bigInteger('total_bayar');//total angsuran yang telah dibayar
            $table->bigInteger('sisa'); //sisa hutang dan selalu diupdate ketika dilakukan pembayaran angsuran
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
        Schema::dropIfExists('credit_application_olds');
    }
}
