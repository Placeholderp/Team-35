<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix order_items table issues
     *
     * @return void
     */
    public function up()
    {
        // First, let's check if there are any order items without a valid order_id
        $orphanedItems = DB::table('order_items')
            ->whereNull('order_id')
            ->orWhere('order_id', 0)
            ->get();

        if ($orphanedItems->count() > 0) {
            // We need to fix these orphaned items
            // Option 1: Create a placeholder order for them
            $placeholderId = DB::table('orders')->insertGetId([
                'total_price' => 0,
                'status' => 'placeholder',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Assign the orphaned items to this placeholder order
            DB::table('order_items')
                ->whereNull('order_id')
                ->orWhere('order_id', 0)
                ->update(['order_id' => $placeholderId]);
        }

        // Now, make sure the order_id column has the proper constraints
        Schema::table('order_items', function (Blueprint $table) {
            // First check if there's a foreign key constraint
            $hasForeignKey = $this->checkForeignKeyExists('order_items', 'order_id');
            
            // If no foreign key, add it
            if (!$hasForeignKey) {
                // Drop the existing foreign key if it exists but is improperly defined
                try {
                    $table->dropForeign(['order_id']);
                } catch (\Exception $e) {
                    // No existing foreign key to drop
                }
                
                $table->foreign('order_id')
                      ->references('id')
                      ->on('orders')
                      ->onDelete('cascade'); // If an order is deleted, delete its items too
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
};