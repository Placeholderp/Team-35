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
    Schema::create('inventory_movements', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained();
        $table->integer('quantity');
        $table->integer('previous_quantity');
        $table->integer('new_quantity');
        $table->string('type'); // purchase, sale, adjustment, etc.
        $table->string('reference')->nullable(); // order number, etc.
        $table->text('notes')->nullable();
        $table->foreignId('created_by')->constrained('users');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_movements');
    }
};
