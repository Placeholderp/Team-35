<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // First, drop the foreign key constraint
            $table->dropForeign(['category_id']);
            
            // Then drop the column
            $table->dropColumn('category_id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // If you need to rollback, you can add the column back
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->onDelete('set null');
        });
    }
};