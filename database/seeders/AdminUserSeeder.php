<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'saeedmubeen20@gmail.com'],
            [
                'name' => 'Admin',
                'password' => '12345678', // Model will auto-hash due to 'hashed' cast
                'role' => 'admin',
                'status' => 'active',
            ]
        );
    }
}

