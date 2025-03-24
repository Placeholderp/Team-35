<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to fix the order_details table.
     *
     * @return void
     */
    public function up()
    {
        // First check if user_id column exists before adding it
        if (!Schema::hasColumn('order_details', 'user_id')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->nullable();
                $table->foreign('user_id')->references('id')->on('users');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            if (Schema::hasColumn('order_details', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};