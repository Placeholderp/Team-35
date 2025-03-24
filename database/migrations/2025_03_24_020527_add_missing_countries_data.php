<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddMissingCountriesData extends Migration
{
    /**
     * Run the migration.
     *
     * @return void
     */
    public function up()
    {
        // Insert data directly using DB facade with raw queries to avoid timestamp fields
        DB::table('countries')->insert([
            ['code' => 'GB', 'name' => 'United Kingdom'],
            // Add any other countries you need here
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        // Remove the added countries
        DB::table('countries')->whereIn('code', ['GB'])->delete();
    }
}