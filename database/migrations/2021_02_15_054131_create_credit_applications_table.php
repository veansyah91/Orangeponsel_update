<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */ 
    public function up()
    {
        Schema::create('credit_applications', function (Blueprint $table) {
            $table->id();
            // Outlet Pengajuan
            $table->unsignedBigInteger('outlet_id');
            $table->unsignedBigInteger('credit_customer_id');
            $table->unsignedBigInteger('credit_partner_id');
            $table->string('merk');
            $table->integer('tenor')->nullable();
            $table->integer('dp')->nullable();
            $table->integer('angsuran')->nullable();
            $table->enum('status', ['pending','accept','reject']);
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            
            $table->timestamps();
            
            $table->foreign('credit_customer_id')->references('id')->on('credit_customers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('credit_partner_id')->references('id')->on('credit_partners')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_applications');
    }
}
