<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditTypeDataStatusColomnCreditApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('credit_applications', function(Blueprint $table)
        {
            $table->dropColumn('status');
        });

        Schema::table('credit_applications', function(Blueprint $table)
        {
            $table->enum('status', ['pending', 'reject', 'accept', 'taken']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_applications', function (Blueprint $table) {
            //
        });
    }
}
