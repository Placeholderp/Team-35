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
    Schema::table('products', function (Blueprint $table) {
        $table->string('sku')->nullable()->after('slug');
        $table->integer('quantity')->default(0)->after('price');
        $table->integer('reorder_level')->default(5)->after('quantity');
        $table->boolean('track_inventory')->default(true)->after('reorder_level');
        $table->boolean('allow_backorder')->default(false)->after('track_inventory');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sku', 'quantity', 'reorder_level', 'track_inventory', 'allow_backorder']);
        });
    }
};
