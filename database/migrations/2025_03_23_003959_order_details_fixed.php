<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First check if the order_details table has a primary key
        $hasPrimaryKey = false;
        
        if (Schema::hasTable('order_details')) {
            // Get column information
            $columns = DB::select("SHOW COLUMNS FROM order_details");
            foreach ($columns as $column) {
                if ($column->Key == 'PRI') {
                    $hasPrimaryKey = true;
                    break;
                }
            }
        }

        // Add a primary key if needed
        if (Schema::hasTable('order_details') && !$hasPrimaryKey) {
            Schema::table('order_details', function (Blueprint $table) {
                // Add ID column
                $table->id()->first();
            });
        }

        // Now add the order_id foreign key
        Schema::table('order_details', function (Blueprint $table) {
            // Check if order_id already exists
            if (!Schema::hasColumn('order_details', 'order_id')) {
                $table->foreignId('order_id')->after('id')->nullable();
                $table->foreign('order_id')->references('id')->on('orders');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            if (Schema::hasColumn('order_details', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }
        });
    }
};