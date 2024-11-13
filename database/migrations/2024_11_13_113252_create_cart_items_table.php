<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {

            $table->foreignId('CartID')->references('CartID')->on('carts')->onDelete('cascade');
            $table->foreignId('ProductID')->references('ProductID')->on('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->primary(['CartID', 'ProductID']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
