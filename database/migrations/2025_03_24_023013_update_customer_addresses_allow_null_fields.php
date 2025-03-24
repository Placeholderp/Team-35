<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            // Modify address2 to allow NULL values
            $table->string('address2', 255)->nullable()->change();
            
            // Optional: Also make these other fields nullable as they're often optional
            // $table->string('city', 255)->nullable()->change();
            // $table->string('zipcode', 45)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            // Revert changes - make address2 NOT NULL again
            $table->string('address2', 255)->nullable(false)->change();
            
            // Revert other fields if you modified them
            // $table->string('city', 255)->nullable(false)->change();
            // $table->string('zipcode', 45)->nullable(false)->change();
        });
    }
};