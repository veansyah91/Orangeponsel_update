<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_ktp');
            $table->string('no_kk');
            $table->string('jenis_kelamin');
            $table->string('alamat');
            $table->string('no_hp');
            

            // jangan lupa dibuatkan mitra kredit
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
        Schema::dropIfExists('credit_customers');
    }
}
