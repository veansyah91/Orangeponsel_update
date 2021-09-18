<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditCollectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_collects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credit_app_old_id')->nullable();
            $table->unsignedBigInteger('credit_application_id')->nullable();
            $table->string('nama');
            $table->string('no_hp');
            $table->boolean('pengambilan_lama');
            $table->integer('terlambat');//update setia hari
            $table->date('tenggang')->nullable();
            $table->string('keterangan')->nullable();

            $table->timestamps();

            $table->foreign('credit_app_old_id')->references('id')->on('credit_application_olds')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('credit_application_id')->references('id')->on('credit_applications')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_collects');
    }
}
