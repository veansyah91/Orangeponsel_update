<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outlet_id');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
            $table->string('supplier_name');

            $table->date('date_delivery');//tanggal pembuatan/pengajuan return
            $table->date('date_accepted_on_supplier')->nullable();//tanggal penerimaan return pada supplier
            $table->date('date_receipt')->nullable();//tanggal penerimaan return pada supplier / pembayaran return
            $table->bigInteger('value');
            $table->bigInteger('value_approvement')->nullable();
            $table->boolean('approvement')->default(false);
            $table->enum('approvement_description', ['menunggu', 'selesai'])->default('menunggu');
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
        Schema::dropIfExists('purchase_returns');
    }
}
