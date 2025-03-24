<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, update any string values to integers
        DB::statement("UPDATE customers SET status = CASE 
            WHEN status = 'active' THEN 1 
            WHEN status = 'disabled' THEN 0 
            WHEN status = '1' THEN 1
            WHEN status = '0' THEN 0
            ELSE 1 END");
        
        // Change the column type
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('status')->default(1)->change();
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('status', 45)->nullable()->change();
        });
    }
};