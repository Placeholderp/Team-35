<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Add email field after last_name
            $table->string('email')->after('last_name')->nullable();
            
            // Change status to boolean if preferred (optional)
            // If you do this, modify this to match how you're using status field
            $table->boolean('status')->default(true)->change();
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('email');
            // Revert status back to string if you changed it
            $table->string('status', 45)->nullable()->change();
        });
    }
};