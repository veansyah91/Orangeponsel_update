<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColomnToAccountReceivableDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_receivable_details', function (Blueprint $table) {
            $table->date('due_date')->nullable();
            $table->boolean('is_paid')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_receivable_details', function (Blueprint $table) {
            //
        });
    }
}
