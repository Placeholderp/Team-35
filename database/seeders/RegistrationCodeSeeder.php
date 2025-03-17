<?php

namespace Database\Seeders;

use App\Models\RegistrationCode;
use Illuminate\Database\Seeder;

class RegistrationCodeSeeder extends Seeder
{
    public function run(): void
    {
        RegistrationCode::create([
            'code' => 'ADMIN123',  
            'type' => 'admin',
            'active' => true
        ]);
    }
}