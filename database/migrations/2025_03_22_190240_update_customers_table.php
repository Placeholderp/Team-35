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
    Schema::table('customers', function (Blueprint $table) {
        // Add email field
        $table->string('email')->after('last_name')->nullable();
        
        // Fix the primary key if needed (only if you want to keep user_id as PK in your model)
        // If using this approach, you'll need to handle data migration carefully
        // $table->renameColumn('id', 'user_id');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
