<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColomnToExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('supplier_name')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            //
        });
    }
}
