<?php

namespace Database\Seeders;

use App\Models\RegistrationCode;
use Illuminate\Database\Seeder;

class RegistrationCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the admin registration code
        RegistrationCode::create([
            'code' => 'ADMIN123',
            'type' => 'admin',
            'active' => true,
        ]);
    }
}