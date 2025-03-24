<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations to fix various database issues.
     *
     * @return void
     */
    public function up()
    {
        // Fix 1: Resolve duplicate user_id column in order_details
        if (Schema::hasColumn('order_details', 'user_id')) {
            // Check if the column has a foreign key constraint
            $hasForeignKey = $this->checkForeignKeyExists('order_details', 'user_id');
            
            // If no foreign key, add it
            if (!$hasForeignKey) {
                Schema::table('order_details', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users');
                });
            }
        }
        
        // Fix 2: Fix customers table relationship after column rename
        if (Schema::hasTable('customer_addresses') && 
            Schema::hasColumn('customers', 'user_id')) {
            
            // Check if foreign key to old 'id' column exists
            $hasOldFK = $this->checkForeignKeyExists('customer_addresses', 'customer_id');
            
            if ($hasOldFK) {
                Schema::table('customer_addresses', function (Blueprint $table) {
                    $table->dropForeign(['customer_id']);
                });
                
                // Update the references
                DB::statement('UPDATE customer_addresses SET customer_id = 
                    (SELECT user_id FROM customers WHERE customers.user_id = customer_addresses.customer_id)');
                
                // Rename column and add new foreign key
                Schema::table('customer_addresses', function (Blueprint $table) {
                    $table->renameColumn('customer_id', 'user_id');
                    $table->foreign('user_id')->references('id')->on('users');
                });
            }
        }
        
        // Fix 3: Add missing foreign key between customers.user_id and users.id
        if (Schema::hasTable('customers') && 
            Schema::hasColumn('customers', 'user_id')) {
            
            $hasForeignKey = $this->checkForeignKeyExists('customers', 'user_id');
            
            if (!$hasForeignKey) {
                Schema::table('customers', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users');
                });
            }
        }
        
        // Fix 4: Ensure order_details has consistent structure
        if (Schema::hasTable('order_details')) {
            // Make sure it has a primary key
            $hasPrimaryKey = $this->checkPrimaryKeyExists('order_details');
            
            if (!$hasPrimaryKey) {
                // Add ID column as primary key
                Schema::table('order_details', function (Blueprint $table) {
                    $table->id()->first();
                });
            }
            
            // Ensure order_id has proper foreign key constraints
            if (Schema::hasColumn('order_details', 'order_id')) {
                $hasForeignKey = $this->checkForeignKeyExists('order_details', 'order_id');
                
                if (!$hasForeignKey) {
                    Schema::table('order_details', function (Blueprint $table) {
                        $table->foreign('order_id')->references('id')->on('orders');
                    });
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // We won't provide a down() method since these are fixes
        // that shouldn't be reverted automatically
    }
    
    /**
     * Check if a foreign key constraint exists on a column
     *
     * @param string $table
     * @param string $column
     * @return bool
     */
    private function checkForeignKeyExists($table, $column)
    {
        $foreignKeys = DB::select(
            "SELECT * FROM information_schema.KEY_COLUMN_USAGE
             WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = ? 
             AND COLUMN_NAME = ?
             AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$table, $column]
        );
        
        return !empty($foreignKeys);
    }
    
    /**
     * Check if a table has a primary key
     *
     * @param string $table
     * @return bool
     */
    private function checkPrimaryKeyExists($table)
    {
        $primaryKeys = DB::select(
            "SELECT * FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = ? 
             AND CONSTRAINT_NAME = 'PRIMARY'",
            [$table]
        );
        
        return !empty($primaryKeys);
    }
};